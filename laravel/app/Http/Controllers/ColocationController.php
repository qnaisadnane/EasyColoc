<?php

namespace App\Http\Controllers;
use App\Models\Colocation;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ColocationController extends Controller
{
    
    public function index()
    {
        $activeColocation = auth()->user()->activeColocation();

        if ($activeColocation) {
            return redirect()->route('colocations.show', $activeColocation);
        }

        return view('colocations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->activeColocation()) {
            return redirect()->route('colocations.index')->with('error', 'Vous appartenez deja a une colocation active.');
        }

        return view('colocations.create');
    }

    
    public function store(Request $request)
    {
        if (auth()->user()->activeColocation()) {
            return redirect()->back()->withErrors(['name' => 'Vous avez deja une colocation active.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $colocation = Colocation::create([
            'name' => $request->name,
            'status' => 'active',
        ]);

        $colocation->members()->attach(auth()->id(), ['role' => 'owner']);

        return redirect()->route('colocations.show', $colocation)->with('success', 'Colocation creee avec succes !');
    }

    
    public function show(Request $request, Colocation $colocation)
    {
        // Verifier si l'utilisateur est membre actif
        $membership = $colocation->members()->where('user_id', auth()->id())->whereNull('left_at')->first();
        
        if (!$membership) {
            abort(403);
        }

        // Filtres de mois et annee (defaut : null pour "toutes les dates")
        $month = $request->get('month');
        $year = $request->get('year');

        $query = $colocation->expenses()->with(['payer', 'category']);
        
        if ($month) {
            $year = $year ?: date('Y');
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }
        
        $expenses = $query->orderBy('date', 'desc')->get();

        // Calculer les statistiques par categorie
        $stats = $expenses->groupBy('category_id')->map(function ($group) {
            return [
                'name' => $group->first()->category->name,
                'total' => $group->sum('amount'),
                'color' => $group->first()->category->color,
            ];
        });

        // Definir la plage de dates pour le calcul des membres historiques
        if ($month) {
            $year = $year ?: date('Y');
            $startRange = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endRange = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        } else {
            $startRange = Carbon::createFromTimestamp(0); 
            $endRange = Carbon::now()->addYears(10); 
        }

        // Recuperer les membres historiques
        $historicalMembersRaw = $colocation->members()
            ->where('colocation_user.created_at', '<=', $endRange)
            ->where(function($query) use ($startRange) {
                $query->whereNull('colocation_user.left_at')
                      ->orWhere('colocation_user.left_at', '>=', $startRange);
            })
            ->get();
        
        $historicalMembers = $historicalMembersRaw->groupBy('id');

        // Calculer les balances basees sur la presence EFFECTIVE lors de chaque depense
        $balances = $historicalMembers->map(function ($memberPeriods) use ($expenses, $colocation) {
            $member = $memberPeriods->first(); 
            $totalPaid = $expenses->where('user_id', $member->id)->sum('amount');
            $totalDebtShare = 0;

            foreach ($expenses as $expense) {
                $expenseDate = Carbon::parse($expense->date);
                
                // Compter les membres DISTINCTS presents au MOMENT de cette depense precise
                $presentCount = $colocation->members()
                    ->where('colocation_user.created_at', '<=', $expenseDate->clone()->endOfDay())
                    ->where(function($q) use ($expenseDate) {
                        $q->whereNull('colocation_user.left_at')
                          ->orWhere('colocation_user.left_at', '>=', $expenseDate->clone()->startOfDay());
                    })
                    ->distinct('users.id')
                    ->count('users.id');

                // Est-ce que cet utilisateur etait la dans l'une de ses periodes ?
                $wasPresent = false;
                foreach ($memberPeriods as $period) {
                    if (($period->pivot->created_at <= $expenseDate->clone()->endOfDay()) && 
                        (is_null($period->pivot->left_at) || $period->pivot->left_at >= $expenseDate->clone()->startOfDay())) {
                        $wasPresent = true;
                        break;
                    }
                }

                if ($wasPresent && $presentCount > 0) {
                    $totalDebtShare += $expense->amount / $presentCount;
                }
            }

            return [
                'user' => $member,
                'paid' => $totalPaid,
                'debtShare' => $totalDebtShare,
            ];
        });

        // Charger les reglements payes
        $settlementQuery = \App\Models\Settlement::where('colocation_id', $colocation->id)
            ->where('status', 'paid');
            
        if ($month && $year) {
            $settlementQuery->where('month', (string)$month)->where('year', (string)$year);
        }
        
        $settlements = $settlementQuery->get();

        // Finaliser le calcul (Integration des reglements)
        $balances = $balances->map(function ($b) use ($settlements) {
            $received = $settlements->where('creditor_id', $b['user']->id)->sum('amount');
            $sent = $settlements->where('debtor_id', $b['user']->id)->sum('amount');

            return array_merge($b, [
                'received' => $received,
                'sent' => $sent,
                'balance' => ($b['paid'] - $b['debtShare']) - $received + $sent,
            ]);
        });

        $colocation->setRelation('members', $historicalMembersRaw->sortBy(function($m) {
            return $m->pivot->left_at === null ? 0 : 1;
        })->unique('id'));
        
        $categories = \App\Models\Category::all();
        $totalMonthly = $expenses->sum('amount');
        $fairShare = $historicalMembers->count() > 0 ? $totalMonthly / $historicalMembers->count() : 0;

        // Simplification des dettes (calculer qui doit a qui)
        $suggestedSettlements = $this->simplifyDebts($balances, $colocation->id);

        return view('colocations.show', compact(
            'colocation', 
            'expenses', 
            'stats', 
            'totalMonthly', 
            'month', 
            'year', 
            'categories', 
            'balances', 
            'fairShare',
            'suggestedSettlements'
        ));
    }

    /**
     * Algorithme greedy de simplification des dettes
     */
    private function simplifyDebts($balances, $colocationId)
    {
        $debtors = [];
        $creditors = [];

        foreach ($balances as $b) {
            $amount = round($b['balance'], 2);
            if ($amount < -0.01) {
                $debtors[] = ['user' => $b['user'], 'amount' => abs($amount)];
            } elseif ($amount > 0.01) {
                $creditors[] = ['user' => $b['user'], 'amount' => $amount];
            }
        }

        $suggested = [];
        $i = 0; $j = 0;

        while ($i < count($debtors) && $j < count($creditors)) {
            $debtor = &$debtors[$i];
            $creditor = &$creditors[$j];

            $payment = min($debtor['amount'], $creditor['amount']);
            
            $suggested[] = [
                'debtor' => $debtor['user'],
                'creditor' => $creditor['user'],
                'amount' => $payment,
            ];

            $debtor['amount'] -= $payment;
            $creditor['amount'] -= $payment;

            if ($debtor['amount'] < 0.01) $i++;
            if ($creditor['amount'] < 0.01) $j++;
        }

        return $suggested;
    }


    
    public function leave(Colocation $colocation)
    {
        $user = auth()->user();
        $activeMembers = $colocation->members()->whereNull('left_at')->get();
        $isOwner = $colocation->owner->contains($user);

        // Si c'est le proprietaire et qu'il y a d'autres membres
        if ($isOwner && $activeMembers->count() > 1) {
            return redirect()->back()->with('error', 'En tant que proprietaire, vous devez retirer tous les autres membres avant de pouvoir quitter et fermer la colocation.');
        }

        // Calculer le solde avant de partir pour la reputation
        $balance = $this->calculateMemberBalance($colocation, $user);

        if (abs($balance) > 0.01) {
            if ($balance < -0.01) {
                $user->decrement('reputation');
            } else {
                $user->increment('reputation');
            }

            // Si ce n'est pas l'owner qui part (car l'owner ne peut partir que s'il est seul)
            // On transfere son solde (positif ou negatif) a l'owner actuel pour equilibrer
            if (!$isOwner) {
                $owner = $colocation->owner()->first();
                if ($owner) {
                    \App\Models\Settlement::create([
                        'colocation_id' => $colocation->id,
                        'debtor_id' => $balance < 0 ? $user->id : $owner->id,
                        'creditor_id' => $balance < 0 ? $owner->id : $user->id,
                        'amount' => abs($balance),
                        'month' => now()->month,
                        'year' => now()->year,
                        'status' => 'paid',
                        'paid_at' => now()
                    ]);
                }
            }
        } else {
            $user->increment('reputation');
        }

        // Marquer le depart
        $colocation->members()->updateExistingPivot($user->id, ['left_at' => now()]);

        // Si c'etait le dernier membre (donc le proprietaire), on desactive la coloc
        if ($isOwner) {
            $colocation->update(['status' => 'cancelled']);
        }

        return redirect()->route('dashboard')->with('success', 'Vous avez quitte la colocation.');
    }

    /**
     * Remove a member from the colocation (Owner only).
     */
    public function removeMember(Colocation $colocation, User $user)
    {
        if (!$colocation->owner->contains(auth()->user())) {
            abort(403);
        }

        if ($colocation->owner->contains($user)) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous retirer vous-même.');
        }

        // Calculer le solde avant l'exclusion
        $balance = $this->calculateMemberBalance($colocation, $user);

        if (abs($balance) > 0.01) {
            if ($balance < -0.01) {
                $user->decrement('reputation');
            } else {
                $user->increment('reputation');
            }
            
            // Transfert du solde (positif ou negatif) au proprietaire
            $owner = auth()->user();

            \App\Models\Settlement::create([
                'colocation_id' => $colocation->id,
                'debtor_id' => $balance < 0 ? $user->id : $owner->id,
                'creditor_id' => $balance < 0 ? $owner->id : $user->id,
                'amount' => abs($balance),
                'month' => now()->month,
                'year' => now()->year,
                'status' => 'paid',
                'paid_at' => now()
            ]);
        } else {
            $user->increment('reputation');
        }

        // Marquer le depart definitif (pivot)
        $colocation->members()->updateExistingPivot($user->id, ['left_at' => now()]);

        return redirect()->back()->with('success', 'Membre retire avec succes.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function calculateMemberBalance(Colocation $colocation, $user)
    {
        $month = now()->month;
        $year = now()->year;
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $expenses = $colocation->expenses()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
        
        $totalPaid = $expenses->where('user_id', $user->id)->sum('amount');
        $totalDebtShare = 0;

        foreach ($expenses as $expense) {
            $expenseDate = Carbon::parse($expense->date);
            
            $presentCount = $colocation->members()
                ->where('colocation_user.created_at', '<=', $expenseDate->clone()->endOfDay())
                ->where(function($q) use ($expenseDate) {
                    $q->whereNull('colocation_user.left_at')
                      ->orWhere('colocation_user.left_at', '>=', $expenseDate->clone()->startOfDay());
                })
                ->distinct('users.id')
                ->count('users.id');

            // Retrouver toutes les periodes de membership cet utilisateur
            $memberships = $colocation->members()
                ->where('users.id', $user->id)
                ->get();

            $wasPresent = false;
            foreach ($memberships as $membership) {
                if (($membership->pivot->created_at <= $expenseDate->clone()->endOfDay()) && 
                    (is_null($membership->pivot->left_at) || $membership->pivot->left_at >= $expenseDate->clone()->startOfDay())) {
                    $wasPresent = true;
                    break;
                }
            }

            if ($wasPresent && $presentCount > 0) {
                $totalDebtShare += $expense->amount / $presentCount;
            }
        }
        
        $settlements = \App\Models\Settlement::where('colocation_id', $colocation->id)
            ->where('month', (string)$month)
            ->where('year', (string)$year)
            ->where('status', 'paid')
            ->get();

        $received = $settlements->where('creditor_id', $user->id)->sum('amount');
        $sent = $settlements->where('debtor_id', $user->id)->sum('amount');

        return ($totalPaid - $totalDebtShare) - $received + $sent;
    }
}
