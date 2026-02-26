<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (User::count() === 1) {
            $user->update(['role' => 'admin']);
        }

        event(new Registered($user));

        Auth::login($user);

        if (session()->has('invitation_token')) {
            $invitation = \App\Models\Invitation::where('token', session('invitation_token'))->where('status', 'pending')->first();
            if ($invitation && $invitation->email === $user->email) {
                $invitation->colocation->members()->attach($user->id, ['role' => 'member']);
                $invitation->update(['status' => 'accepted']);
                session()->forget('invitation_token');
                return redirect()->route('colocations.show', $invitation->colocation)->with('success', 'Bienvenue dans votre nouvelle colocation !');
            }
        }

        return redirect(route('dashboard', absolute: false));
    }
}
