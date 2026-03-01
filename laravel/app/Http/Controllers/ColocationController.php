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
            return redirect()->route('colocations.index')->with('error', 'Vous appartenez déjà à une colocation active.');
        }

        return view('colocations.create');
    }

    
    public function store(Request $request)
    {
        if (auth()->user()->activeColocation()) {
            return redirect()->back()->withErrors(['name' => 'Vous avez déjà une colocation active.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $colocation = Colocation::create([
            'name' => $request->name,
            'status' => 'active',
        ]);

        $colocation->members()->attach(auth()->id(), ['role' => 'owner']);

        return redirect()->route('colocations.show', $colocation)->with('success', 'Colocation créée avec succès !');
    }

    
    public function show(Request $request, Colocation $colocation)
    {
        // Vérifier si l'utilisateur est membre actif
        $membership = $colocation->members()->where('user_id', auth()->id())->whereNull('left_at')->first();
        
        if (!$membership) {
            abort(403);
        }

        // Filtres de mois et année (défaut : null pour "toutes les dates")
        $month = $request->get('month');
        $year = $request->get('year');

        $query = $colocation->expenses()->with(['payer', 'category']);
        
        if ($month && $year) {
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }
        
        $expenses = $query->orderBy('date', 'desc')->get();

        // Calculer les statistiques par catégorie
        $stats = $expenses->groupBy('category_id')->map(function ($group) {
            return [
                'name' => $group->first()->category->name,
                'total' => $group->sum('amount'),
                'color' => $group->first()->category->color,
                'icon' => $group->first()->category->icon,
            ];
        });

        // Définir la plage de dates pour le calcul des membres historiques
        if ($month && $year) {
            $startRange = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endRange = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        } else {
            $startRange = Carbon::createFromTimestamp(0); // Depuis toujours
            $endRange = Carbon::now()->addYears(10); // Jusqu'à l'infini (presque)
        }

        // Récupérer les membres historiques
        $historicalMembersRaw = $colocation->members()
            ->where('colocation_user.created_at', '<=', $endRange)
            ->where(function($query) use ($startRange) {
                $query->whereNull('colocation_user.left_at')
                      ->orWhere('colocation_user.left_at', '>=', $startRange);
            })
            ->get();
        
        $historicalMembers = $historicalMembersRaw->groupBy('id');

        // Calculer les balances basées sur la présence EFFECTIVE lors de chaque dépense
        $balances = $historicalMembers->map(function ($memberPeriods) use ($expenses, $colocation) {
            $member = $memberPeriods->first(); // On prend l'objet user de base
            $totalPaid = $expenses->where('user_id', $member->id)->sum('amount');
            $totalDebtShare = 0;

            foreach ($expenses as $expense) {
                $expenseDate = Carbon::parse($expense->date);
                
                // Compter les membres DISTINCTS présents au MOMENT de cette dépense précise
                $presentCount = $colocation->members()
                    ->where('colocation_user.created_at', '<=', $expenseDate->clone()->endOfDay())
                    ->where(function($q) use ($expenseDate) {
                        $q->whereNull('colocation_user.left_at')
                          ->orWhere('colocation_user.left_at', '>=', $expenseDate->clone()->startOfDay());
                    })
                    ->distinct('users.id')
                    ->count('users.id');

                // Est-ce que cet utilisateur était là dans l'une de ses périodes ?
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

        // Charger les règlements payés
        $settlementQuery = \App\Models\Settlement::where('colocation_id', $colocation->id)
            ->where('status', 'paid');
            
        if ($month && $year) {
            $settlementQuery->where('month', (string)$month)->where('year', (string)$year);
        }
        
        $settlements = $settlementQuery->get();

        // Finaliser le calcul (Intégration des règlements)
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

        // Simplification des dettes (calculer qui doit à qui)
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

        // Si c'est le propriétaire et qu'il y a d'autres membres
        if ($isOwner && $activeMembers->count() > 1) {
            return redirect()->back()->with('error', 'En tant que propriétaire, vous devez retirer tous les autres membres avant de pouvoir quitter et fermer la colocation.');
        }

        // Calculer le solde avant de partir pour la réputation
        $balance = $this->calculateMemberBalance($colocation, $user);

        if ($balance < -0.01) {
            $user->decrement('reputation');

            // Si ce n'est pas l'owner qui part (car l'owner ne peut partir que s'il est seul)
            // On transfère sa dette à l'owner actuel
            if (!$isOwner) {
                $owner = $colocation->owner()->first();
                if ($owner) {
                    \App\Models\Settlement::create([
                        'colocation_id' => $colocation->id,
                        'debtor_id' => $user->id,
                        'creditor_id' => $owner->id,
                        'amount' => abs($balance),
                        'month' => now()->month,
                        'year' => now()->year,
                        'status' => 'paid'
                    ]);
                }
            }
        } else {
            $user->increment('reputation');
        }

        // Marquer le départ
        $colocation->members()->updateExistingPivot($user->id, ['left_at' => now()]);

        // Si c'était le dernier membre (donc le propriétaire), on désactive la coloc
        if ($isOwner) {
            $colocation->update(['status' => 'cancelled']);
        }

        return redirect()->route('dashboard')->with('success', 'Vous avez quitté la colocation.');
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

        if ($balance < -0.01) {
            $user->decrement('reputation');
            
            // Transfert de dette au propriétaire via un règlement interne
            $debtAmount = abs($balance);
            $owner = auth()->user();

            \App\Models\Settlement::create([
                'colocation_id' => $colocation->id,
                'debtor_id' => $user->id,
                'creditor_id' => $owner->id,
                'amount' => $debtAmount,
                'month' => now()->month,
                'year' => now()->year,
                'status' => 'paid'
            ]);
        } else if ($balance > 0.01) {
            $user->increment('reputation');
        }

        // Marquer le départ définitif (pivot)
        $colocation->members()->updateExistingPivot($user->id, ['left_at' => now()]);

        return redirect()->back()->with('success', 'Membre retiré avec succès.');
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

            // Retrouver toutes les périodes de membership cet utilisateur
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
