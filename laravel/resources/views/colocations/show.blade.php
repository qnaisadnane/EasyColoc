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
            
            <div class="flex items-center space-x-4">
                <!-- Filtre Temporel -->
                <form method="GET" class="flex items-center bg-white/5 border border-white/10 rounded-2xl p-1">
                    <select name="month" onchange="this.form.submit()" class="bg-transparent border-none text-slate-300 font-black text-xs uppercase tracking-widest focus:ring-0 cursor-pointer">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="year" onchange="this.form.submit()" class="bg-transparent border-none text-slate-300 font-black text-xs uppercase tracking-widest focus:ring-0 cursor-pointer">
                        @for($y=date('Y'); $y>=date('Y')-2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>

                @php
                    $isOwner = $colocation->owner->contains(auth()->user());
                @endphp

                @if($isOwner)
                    <div xl-glass class="p-1 rounded-2xl border border-white/5 shadow-2xl overflow-hidden group">
                        <form action="{{ route('invitations.store', $colocation) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input name="email" type="email" placeholder="Email" class="pl-4 pr-2 py-2 bg-transparent border-none focus:ring-0 rounded-xl text-xs text-white placeholder:text-slate-600 w-32 font-bold" required />
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl transition-all active:scale-95 text-[10px] uppercase tracking-widest">
                                INVITER
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ openExpenseModal: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Flux de Dépenses -->
                <div class="lg:col-span-8 space-y-10">
                    <div xl-glass class="p-10 rounded-[3rem] relative overflow-hidden group min-h-[500px]">
                        <div class="flex items-center justify-between mb-10 relative z-10">
                            <h3 class="text-2xl font-black text-white tracking-tight">Flux de dépenses</h3>
                            <button @click="openExpenseModal = true" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 text-xs uppercase tracking-widest">
                                + NOUVELLE DÉPENSE
                            </button>
                        </div>
                        
                        @if($expenses->isEmpty())
                            <div class="flex flex-col items-center justify-center p-16 rounded-[2.5rem] bg-white/[0.02] border border-white/5 border-dashed border-2 group-hover:border-indigo-500/30 transition-all duration-500 mt-10">
                                <div class="h-24 w-24 rounded-[2rem] bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-8">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-bold text-xl mb-2">Le calme plat...</p>
                                <p class="text-slate-500 text-sm font-medium">Aucune dépense en {{ Carbon\Carbon::create()->month((int) $month)->translatedFormat('F') }}.</p>
                            </div>
                        @else
                            <div class="space-y-4 relative z-10">
                                @foreach($expenses as $expense)
                                    <div class="flex items-center justify-between p-6 rounded-3xl bg-white/[0.03] border border-white/5 hover:border-indigo-500/30 hover:bg-white/[0.05] transition-all group/item">
                                        <div class="flex items-center space-x-6">
                                            <div class="h-14 w-14 rounded-2xl bg-slate-900 flex items-center justify-center text-indigo-400 border border-white/5 shadow-inner">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-white font-black tracking-tight text-lg">{{ $expense->description }}</p>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest px-2 py-0.5 bg-indigo-500/10 rounded-md">
                                                        {{ $expense->category->name }}
                                                    </span>
                                                    <span class="text-xs text-slate-500 font-bold uppercase">
                                                        Par {{ $expense->payer->id === auth()->id() ? 'moi' : $expense->payer->name }} • {{ \Carbon\Carbon::parse($expense->date)->format('d/m') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right flex items-center space-x-6">
                                            <p class="text-2xl font-black text-white tracking-tighter">{{ number_format($expense->amount, 2) }}€</p>
                                            
                                            @if(auth()->id() === $expense->user_id || $isOwner)
                                                <form action="{{ route('depenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Supprimer cette dépense ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-600 hover:text-red-500 transition-colors opacity-0 group-hover/item:opacity-100">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistiques Crystal -->
                <div class="lg:col-span-4 space-y-10">
                    <div xl-glass class="p-10 rounded-[3rem] border border-white/5 bg-gradient-to-br from-indigo-600/10 to-transparent">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-white tracking-tight">Analyse du Mois</h3>
                            <div class="px-3 py-1 rounded-full bg-indigo-500/20 text-[9px] font-black text-indigo-300 uppercase tracking-widest border border-indigo-500/30">
                                {{ Carbon\Carbon::create()->month((int) $month)->translatedFormat('F') }}
                            </div>
                        </div>

                        <div class="mb-10">
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total injecté</p>
                            <p class="text-5xl font-black text-white tracking-tighter">{{ number_format($totalMonthly, 2) }}€</p>
                        </div>
                        
                        <div class="space-y-6">
                            @forelse($stats as $stat)
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-black text-slate-300 uppercase tracking-wider flex items-center">
                                            <span class="h-2 w-2 rounded-full bg-{{ $stat['color'] }}-500 mr-2 shadow-[0_0_8px_rgba(var(--tw-color-{{ $stat['color'] }}-500),0.5)]"></span>
                                            {{ $stat['name'] }}
                                        </span>
                                        <span class="text-sm font-black text-white">{{ number_format($stat['total'], 2) }}€</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-{{ $stat['color'] }}-500 rounded-full shadow-[0_0_10px_rgba(var(--tw-color-{{ $stat['color'] }}-500),0.3)]" style="width: {{ $totalMonthly > 0 ? ($stat['total'] / $totalMonthly) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-600 text-[10px] font-black uppercase tracking-widest text-center py-10 italic">Aucune donnée disponible</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Escouade -->
                    <div xl-glass class="p-10 rounded-[3rem] border border-white/5">
                        <div class="flex items-center justify-between mb-10">
                            <h3 class="text-2xl font-black text-white tracking-tight">Escouade</h3>
                        </div>
                        
                        <div class="space-y-8">
                            @foreach($colocation->members as $member)
                                <div class="group flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-xl bg-slate-900 border border-white/10 flex items-center justify-center text-indigo-400 font-black shadow-inner">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-white font-black text-sm">{{ $member->name }}</p>
                                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $member->pivot->role }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Nouvelle Dépense -->
        <div x-show="openExpenseModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-cloak>
            <div xl-glass class="w-full max-w-lg p-10 rounded-[3rem] border border-white/10 shadow-2xl relative" @click.away="openExpenseModal = false">
                <button @click="openExpenseModal = false" class="absolute top-8 right-8 text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <h3 class="text-3xl font-black text-white tracking-tighter mb-8">Nouvelle Dépense</h3>

                <form action="{{ route('depenses.store', $colocation) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="description" :value="__('DESCRIPTION')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />
                        <x-text-input id="description" name="description" type="text" class="block w-full" placeholder="Ex: Courses Monoprix" required />
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="amount" :value="__('MONTANT (€)')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="block w-full" placeholder="0.00" required />
                        </div>
                        <div>
                            <x-input-label for="date" :value="__('DATE')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />
                            <x-text-input id="date" name="date" type="date" class="block w-full" value="{{ date('Y-m-d') }}" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="category_id" :value="__('CATÉGORIE')" class="text-[10px] font-black tracking-[0.2em] text-slate-500 mb-2 ml-1" />
                        <select name="category_id" class="block w-full bg-slate-900/50 border-white/10 rounded-2xl text-slate-300 font-bold focus:border-indigo-500/30 focus:ring-0 transition-all">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4">
                        <x-primary-button class="w-full py-4 uppercase tracking-widest font-black">
                            ENREGISTRER LA DÉPENSE
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
