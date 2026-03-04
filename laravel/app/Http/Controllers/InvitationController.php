<?php
namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
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

        $invitation = Invitation::create([
            'email' => $request->email,
            'token' => Str::random(40),
            'colocation_id' => $colocation->id,
            'status' => 'pending',
        ]);

        Mail::to($request->email)->send(new InvitationMail($invitation));

        return redirect()->back()->with('success', 'Invitation envoyée avec succès par email !');
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();

        if (!auth()->check()) {
            session(['invitation_token' => $token]);
            return redirect()->route('register')->with('info', 'Veuillez créer un compte pour rejoindre la colocation.');
        }

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
