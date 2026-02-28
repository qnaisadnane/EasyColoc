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
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // Si user_id est fourni, on vérifie que l'utilisateur qui l'envoie est admin 
        // ou que c'est une action légitime (ex: créancier qui valide un paiement)
        $userId = $request->user_id ?? auth()->id();

        // Sécurité de base : le user_id doit appartenir à la colocation
        if (!$colocation->members()->where('users.id', $userId)->exists()) {
            abort(403);
        }

        $colocation->expenses()->create([
            'user_id' => $userId,
            'category_id' => $request->category_id ?? Category::first()->id,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Transaction enregistrée !');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $colocation = $expense->colocation;
        $isOwner = $colocation->owner()->where('users.id', auth()->id())->exists();
        $isPayer = $expense->user_id === auth()->id();

        if (!$isPayer) {
            abort(403, 'Seul le payeur peut modifier cette dépense.');
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

        if (!$isPayer) {
            abort(403, 'Seul le payeur peut supprimer cette dépense.');
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Dépense supprimée.');
    }
}
