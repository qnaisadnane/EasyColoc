<x-app-layout>
    <x-slot name="header">
        <div class="relative py-4">
            <div class="absolute -left-4 top-0 h-16 w-1 bg-indigo-500 rounded-full shadow-[0_0_20px_rgba(99,102,241,0.5)]"></div>
            <h2 class="font-black text-5xl text-white tracking-tighter">
                {{ __('Nouveau Quartier') }}
            </h2>
            <p class="text-slate-400 mt-2 font-medium">L'aventure commence par un nom.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div xl-glass class="rounded-[3rem] p-10 md:p-16 border border-white/10 relative overflow-hidden group">
                <!-- High-tech backdrop -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/5 to-transparent pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="mb-12">
                        <div class="h-16 w-16 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 mb-6 shadow-inner">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-white tracking-tight">Démarrer une colocation</h3>
                        <p class="text-slate-400 mt-2 font-medium">Donnez un nom unique à votre espace de vie partagé.</p>
                    </div>

                    <form method="POST" action="{{ route('colocations.store') }}" class="space-y-8">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('NOM DE LA COLOCATION')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-3 ml-1" />
                            <x-text-input id="name" class="block w-full py-4 px-6 text-lg font-bold" type="text" name="name" :value="old('name')" placeholder="ex: Le Loft des Geeks" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-3 ml-2" />
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-10 border-t border-white/5">
                            <a href="{{ route('colocations.index') }}" class="text-xs font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">
                                ← ANNULER
                            </a>
                            <x-primary-button class="w-full sm:w-auto px-12 py-5 text-lg">
                                {{ __('LANCER LA COLO') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
