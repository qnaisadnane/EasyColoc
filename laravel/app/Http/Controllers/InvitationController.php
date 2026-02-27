<?php
namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        // Vérifier si l'utilisateur est le propriétaire
        if ($colocation->owner()->first()->id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        Invitation::create([
            'email' => $request->email,
            'token' => Str::random(40),
            'colocation_id' => $colocation->id,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Invitation créée ! L\'utilisateur verra l\'invitation sur son tableau de bord.');
    }

    public function accept(Invitation $invitation)
    {
        $user = auth()->user();

        if ($user->email !== $invitation->email) {
            abort(403, 'Cette invitation ne vous est pas destinée.');
        }

        if ($user->activeColocation()) {
            return redirect()->route('dashboard')->with('error', 'Vous appartenez déjà à une colocation active.');
        }

        $invitation->colocation->members()->attach($user->id, ['role' => 'member']);
        $invitation->update(['status' => 'accepted']);

        // Refuser les autres invitations en attente
        Invitation::where('email', $user->email)
            ->where('status', 'pending')
            ->update(['status' => 'declined']);

        return redirect()->route('colocations.show', $invitation->colocation)->with('success', 'Bienvenue dans la colocation !');
    }

    public function decline(Invitation $invitation)
    {
        if (auth()->user()->email !== $invitation->email) {
            abort(403);
        }

        $invitation->update(['status' => 'declined']);

        return redirect()->route('dashboard')->with('success', 'Invitation refusée.');
    }
}
