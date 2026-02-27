<?php

namespace App\Http\Controllers;
use App\Models\Colocation;
use Illuminate\Http\Request;

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

        // Filtres de mois et année
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Récupérer les dépenses filtrées
        $expenses = $colocation->expenses()
            ->with(['payer', 'category'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        // Calculer les statistiques par catégorie
        $stats = $expenses->groupBy('category_id')->map(function ($group) {
            return [
                'name' => $group->first()->category->name,
                'total' => $group->sum('amount'),
                'color' => $group->first()->category->color,
                'icon' => $group->first()->category->icon,
            ];
        });

        $totalMonthly = $expenses->sum('amount');

        $colocation->load(['members' => function($query) {
            $query->whereNull('left_at');
        }]);
        
        $categories = \App\Models\Category::all();

        return view('colocations.show', compact('colocation', 'expenses', 'stats', 'totalMonthly', 'month', 'year', 'categories'));
    }

    
    public function leave(Colocation $colocation)
    {
        $user = auth()->user();
        
        // Un propriétaire ne peut pas quitter sa propre colocation (il doit l'annuler ou transférer)
        if ($colocation->owner->contains($user)) {
            return redirect()->back()->with('error', 'Le propriétaire ne peut pas quitter la colocation.');
        }

        $colocation->members()->updateExistingPivot($user->id, ['left_at' => now()]);

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
}
