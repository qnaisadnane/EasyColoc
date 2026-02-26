<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 pb-4">
            <div class="flex items-center space-x-6">
                <div class="h-20 w-20 rounded-[2rem] bg-indigo-600 flex items-center justify-center text-white shadow-[0_0_40px_-5px_rgba(99,102,241,0.6)] border border-indigo-400/50">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-black text-5xl text-white tracking-tighter">
                        {{ $colocation->name }}
                    </h2>
                    <div class="flex items-center mt-3 space-x-4 text-slate-500 font-bold text-sm tracking-widest uppercase">
                        <span class="flex items-center text-indigo-400">
                             {{ $colocation->members->count() }} CO-LOCATAIRES
                        </span>
                        <span class="h-1.5 w-1.5 rounded-full bg-slate-800"></span>
                        <span>QUARTIER GÉNÉRAL</span>
                    </div>
                </div>
            </div>
            
            @php
                $isOwner = $colocation->owner->contains(auth()->user());
            @endphp

            @if($isOwner)
                <div xl-glass class="p-2 rounded-3xl border border-white/5 shadow-2xl overflow-hidden group">
                    <form action="{{ route('invitations.store', $colocation) }}" method="POST" class="flex items-center space-x-2">
                        @csrf
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-indigo-500/50 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input name="email" type="email" placeholder="Email du futur coloc" class="pl-11 pr-4 py-3 bg-white/5 border-transparent focus:border-indigo-500/30 focus:bg-indigo-500/5 focus:ring-0 rounded-2xl text-sm text-white placeholder:text-slate-600 transition-all w-48 md:w-64 font-bold" required />
                        </div>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-[1.25rem] transition-all active:scale-95 shadow-lg shadow-indigo-600/20 text-xs uppercase tracking-widest">
                            INVITER
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Dashboard Info -->
                <div class="lg:col-span-8 space-y-10">
                    <div xl-glass class="p-10 rounded-[3rem] relative overflow-hidden group">
                        <!-- Neon Accent -->
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700"></div>
                        
                        <div class="flex items-center justify-between mb-10 relative z-10">
                            <h3 class="text-2xl font-black text-white tracking-tight">Flux de dépenses</h3>
                            <div class="flex space-x-2">
                                <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                <span class="h-2 w-2 rounded-full bg-slate-800"></span>
                                <span class="h-2 w-2 rounded-full bg-slate-800"></span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-center justify-center p-16 rounded-[2.5rem] bg-white/[0.02] border border-white/5 border-dashed border-2 group-hover:border-indigo-500/30 transition-all duration-500">
                            <div class="h-24 w-24 rounded-[2rem] bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-8 shadow-inner transform group-hover:rotate-6 transition-transform">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <p class="text-slate-400 font-bold text-xl mb-2">Le calme plat...</p>
                            <p class="text-slate-500 text-sm font-medium mb-10">Aucune dépense enregistrée dans ce quartier général.</p>
                            
                            <button class="px-8 py-4 bg-white/5 hover:bg-indigo-600 border border-white/10 hover:border-indigo-400 text-slate-300 hover:text-white font-black rounded-2xl transition-all shadow-xl active:scale-95 text-sm tracking-widest uppercase">
                                + NOUVELLE DÉPENSE
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Members List -->
                <div class="lg:col-span-4 space-y-10">
                    <div xl-glass class="p-10 rounded-[3rem] border border-white/5">
                        <div class="flex items-center justify-between mb-10">
                            <h3 class="text-2xl font-black text-white tracking-tight">Escouade</h3>
                            <div class="px-4 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                {{ $colocation->members->count() }} UNITÉS
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            @foreach($colocation->members as $member)
                                <div class="group flex justify-between items-center transition-all">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-tr from-slate-800 to-slate-900 border border-white/10 flex items-center justify-center text-indigo-400 font-black text-xl shadow-inner transition-transform group-hover:scale-110 duration-300">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                            @if($member->id === auth()->id())
                                                <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-green-500 rounded-full border-2 border-slate-950 shadow-[0_0_10px_rgba(34,197,94,0.5)]"></div>
                                            @endif
                                        </div>
                                        <div class="ml-5">
                                            <p class="text-white font-black tracking-tight group-hover:text-indigo-400 transition-colors">{{ $member->name }}</p>
                                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-0.5">
                                                {{ $member->pivot->role === 'owner' ? 'Commandant' : 'Officier' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        @if($member->id === auth()->id())
                                            @if($member->pivot->role !== 'owner')
                                                <form action="{{ route('colocations.leave', $colocation) }}" method="POST" onsubmit="return confirm('Quitter le quartier général ?')">
                                                    @csrf
                                                    <button type="submit" class="p-3 text-slate-600 hover:text-red-500 transition-colors hover:bg-hover:bg-red-500/10 rounded-xl" title="Quitter">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            @if($isOwner && $member->pivot->role !== 'owner')
                                                <form action="{{ route('colocations.removeMember', [$colocation, $member]) }}" method="POST" onsubmit="return confirm('Révoquer l\'accès ?')">
                                                    @csrf
                                                    <button type="submit" class="p-3 text-slate-600 hover:text-red-500 transition-colors hover:bg-red-500/10 rounded-xl opacity-0 group-hover:opacity-100" title="Révoquer">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12H15" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Quick Stats Crystal -->
                    <div xl-glass class="p-10 rounded-[3rem] border border-white/5 bg-gradient-to-br from-indigo-600/10 to-transparent">
                        <h4 class="text-sm font-black text-indigo-400 uppercase tracking-widest mb-6">Résumé Global</h4>
                        <div class="space-y-6">
                            <div class="flex justify-between items-end border-b border-white/5 pb-4">
                                <span class="text-slate-400 text-xs font-bold uppercase">Total Dépenses</span>
                                <span class="text-3xl font-black text-white tracking-tighter">0.00€</span>
                            </div>
                            <div class="flex justify-between items-end border-b border-white/5 pb-4">
                                <span class="text-slate-400 text-xs font-bold uppercase">Ma Balance</span>
                                <span class="text-3xl font-black text-indigo-400 tracking-tighter">0.00€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
