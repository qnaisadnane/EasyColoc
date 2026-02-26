<?php
namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use App\Mail\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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

        $token = Str::random(40);

        $invitation = Invitation::create([
            'email' => $request->email,
            'token' => $token,
            'colocation_id' => $colocation->id,
            'status' => 'pending',
        ]);

        Mail::to($request->email)->send(new InvitationMail($invitation));

        return redirect()->back()->with('success', 'Invitation envoyée avec succès !');
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();

        // Si l'utilisateur n'est pas connecté, rediriger vers register avec le token en session
        if (!auth()->check()) {
            session(['invitation_token' => $token]);
            return redirect()->route('register')->with('info', 'Créez un compte pour rejoindre la colocation.');
        }

        $user = auth()->user();

        // Vérifier si l'utilisateur appartient déjà à une colocation active
        if ($user->activeColocation()) {
            return redirect()->route('dashboard')->with('error', 'Vous appartenez déjà à une colocation active.');
        }

        // Vérifier si l'email correspond (optionnel selon le scénario 1, mais recommandé)
        if ($user->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation ne vous est pas destinée.');
        }

        // Ajouter l'utilisateur à la colocation
        $invitation->colocation->members()->attach($user->id, ['role' => 'member']);
        $invitation->update(['status' => 'accepted']);

        return redirect()->route('colocations.show', $invitation->colocation)->with('success', 'Bienvenue dans la colocation !');
    }
}
