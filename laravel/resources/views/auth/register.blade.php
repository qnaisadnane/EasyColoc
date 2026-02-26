<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-4xl font-black text-white tracking-tighter mb-3">Sign Up.</h2>
        
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('NOM COMPLET')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"  />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('ADRESSE EMAIL')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username"  />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('MOT DE PASSE')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />

            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                             />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('CONFIRMATION MOT DE PASSE')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />

            <x-text-input id="password_confirmation" class="block w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full py-4 text-lg">
                {{ __('Créer mon compte') }}
            </x-primary-button>
        </div>

        <div class="mt-8 text-center border-t border-white/5 pt-8">
            <p class="text-sm text-slate-500 font-medium">
                Déjà inscrit ? 
                <a href="{{ route('login') }}" class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors ml-1">Se connecter</a>
            </p>
        </div>
    </form>
</x-guest-layout>
