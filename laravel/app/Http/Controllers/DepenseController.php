<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Colocation $colocation)
    {
        // Vérifier si l'utilisateur appartient à la colocation
        if (!$colocation->members()->where('user_id', auth()->id())->whereNull('left_at')->exists()) {
            abort(403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $colocation->expenses()->create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Dépense enregistrée avec succès !');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $colocation = $expense->colocation;
        $isOwner = $colocation->owner()->where('users.id', auth()->id())->exists();
        $isPayer = $expense->user_id === auth()->id();

        if (!$isOwner && !$isPayer) {
            abort(403, 'Seul le payeur ou le propriétaire peut modifier cette dépense.');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $expense->update($request->only(['category_id', 'amount', 'description', 'date']));

        return redirect()->back()->with('success', 'Dépense mise à jour !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $colocation = $expense->colocation;
        $isOwner = $colocation->owner()->where('users.id', auth()->id())->exists();
        $isPayer = $expense->user_id === auth()->id();

        if (!$isOwner && !$isPayer) {
            abort(403, 'Seul le payeur ou le propriétaire peut supprimer cette dépense.');
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Dépense supprimée.');
    }
}
