<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-4xl font-black text-white tracking-tighter mb-3">Login.</h2>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('ADRESSE EMAIL')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2 ml-1">
                <x-input-label for="password" :value="__('MOT DE PASSE')" class="text-[10px] font-black tracking-[0.2em] text-slate-500" />
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-widest transition-colors" href="{{ route('password.request') }}">
                        Oublie ?
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                             />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded-lg bg-slate-900 border-white/10 text-indigo-600 shadow-sm focus:ring-indigo-500/20 w-5 h-5 transition-all group-hover:border-indigo-500/50" name="remember">
                <span class="ms-3 text-sm font-bold text-slate-500 group-hover:text-slate-300 transition-colors uppercase tracking-widest">{{ __('Rester connecte') }}</span>
            </label>
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full py-4 text-lg">
                {{ __('Se Connecter') }}
            </x-primary-button>
        </div>

        <div class="mt-8 text-center border-t border-white/5 pt-8">
            <p class="text-sm text-slate-500 font-medium">
                Nouveau ici ? 
                <a href="{{ route('register') }}" class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors ml-1">Creer un compte</a>
            </p>
        </div>
    </form>
</x-guest-layout>
