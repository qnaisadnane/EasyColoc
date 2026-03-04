<?php

namespace App\Http\Controllers;

use App\Models\ShoppingItem;
use App\Models\Colocation;
use Illuminate\Http\Request;

class ShoppingController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:50',
        ]);

        $colocation->shoppingItems()->create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'quantity' => $request->quantity,
        ]);

        return redirect()->back()->with('success', 'Produit ajouté à la liste !');
    }

    public function toggle(ShoppingItem $item)
    {
        $item->update(['is_bought' => !$item->is_bought]);
        return redirect()->back()->with('success', $item->is_bought ? 'Produit acheté !' : 'Produit remis en liste.');
    }

    public function destroy(ShoppingItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Produit retiré.');
    }
}
