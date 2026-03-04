<x-app-layout>
    <x-slot name="header">
        <div class="relative py-4">
            <div class="absolute -left-4 top-0 h-16 w-1 bg-indigo-500 rounded-full shadow-[0_0_20px_rgba(99,102,241,0.5)]"></div>
            <h2 class="font-black text-5xl text-white tracking-tighter">
                {{ __('Mes Colocations') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(isset($colocations) && $colocations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($colocations as $colocation)
                        <a href="{{ route('colocations.show', $colocation) }}" xl-glass class="group p-8 rounded-[2.5rem] hover:border-indigo-500/50 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/10 rounded-bl-[5rem] blur-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex items-start justify-between mb-8">
                                <div class="h-16 w-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-inner">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <div class="px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-[10px] font-black uppercase tracking-widest text-indigo-400">Actif</div>
                            </div>
                            
                            <h3 class="text-2xl font-black text-white group-hover:text-indigo-400 transition-colors tracking-tight">{{ $colocation->name }}</h3>
                            
                            <div class="mt-10 flex items-center justify-between">
                                <div class="flex items-center text-slate-500 text-xs font-bold uppercase tracking-wider">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ $colocation->members->count() }} membres
                                </div>
                                <div class="h-8 w-8 rounded-full border border-white/10 flex items-center justify-center text-slate-400 group-hover:text-white group-hover:border-indigo-500/50 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div xl-glass class="rounded-[3rem] p-16 md:p-24 text-center relative overflow-hidden group">
                    <!-- Decorative Background Glow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/5 to-fuchsia-600/5 opacity-50 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="relative z-10">
                        
                        <h3 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tighter">Ajouter Une colocation <br class="hidden md:block"></h3>
                        <p class="text-slate-400 max-w-lg mx-auto mb-14 leading-relaxed text-lg font-medium">Creez votre première colocation et revolutionnez la gestion de vos depenses communes avec style.</p>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                            <a href="{{ route('colocations.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-5 bg-indigo-600 hover:bg-indigo-500 text-white text-lg font-black rounded-2xl shadow-[0_0_30px_-5px_rgba(99,102,241,0.5)] transition-all transform hover:-translate-y-1 active:scale-95">
                                <svg class="-ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Creer une colocation
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
