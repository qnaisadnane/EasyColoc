<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_colocations' => Colocation::count(),
            'total_expenses' => Expense::sum('amount'),
            'active_colocations' => Colocation::count(), // Simplifié
            'banned_users' => User::where('is_banned', true)->count(),
        ];

        $users = User::orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return view('admin.dashboard', compact('stats', 'users', 'categories'));
    }

    public function toggleBan(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Impossible de bannir un administrateur.');
        }

        $user->update(['is_banned' => !$user->is_banned]);
        
        $status = $user->is_banned ? 'banni' : 'réintégré';
        return redirect()->back()->with('success', "Utilisateur {$status} avec succès.");
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:20',
        ]);

        Category::create($request->all());

        return redirect()->back()->with('success', 'Catégorie créée !');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:20',
        ]);

        $category->update($request->all());

        return redirect()->back()->with('success', 'Catégorie mise à jour !');
    }

    public function destroyCategory(Category $category)
    {
        // On pourrait vérifier si des dépenses y sont liées, mais on va permettre la suppression
        // (Une catégorie par défaut pourrait être utile en production)
        $category->delete();

        return redirect()->back()->with('success', 'Catégorie supprimée.');
    }
}
