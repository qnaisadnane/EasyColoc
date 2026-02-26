<x-app-layout>
    <x-slot name="header">
        <div class="relative py-4">
            <div class="absolute -left-4 top-0 h-16 w-1 bg-indigo-500 rounded-full shadow-[0_0_20px_rgba(99,102,241,0.5)]"></div>
            <h2 class="font-black text-5xl text-white tracking-tighter">
                {{ __('Paramètres Profil') }}
            </h2>
            <p class="text-slate-400 mt-2 font-medium">Gérez votre identité numérique.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div xl-glass class="p-8 sm:p-12 rounded-[3.5rem] border border-white/5">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div xl-glass class="p-8 sm:p-12 rounded-[3.5rem] border border-white/5">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div xl-glass class="p-8 sm:p-12 rounded-[3.5rem] border border-red-500/10">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
