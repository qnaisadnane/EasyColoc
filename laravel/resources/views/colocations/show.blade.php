<x-app-layout>
    @php
        $isOwner = $colocation->owner->contains(auth()->user());
    @endphp
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
                                            
                                            @if(auth()->id() === $expense->user_id)
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

                <!-- Règlements (Balances) -->
                <div class="lg:col-span-4 space-y-10">
                    <div xl-glass class="p-10 rounded-[3rem] border border-white/5 bg-gradient-to-br from-indigo-600/10 to-transparent relative overflow-hidden mb-10">
                        <div class="absolute -top-12 -left-12 h-32 w-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
                        <h3 class="text-xl font-black text-white tracking-tight mb-8 relative z-10 uppercase">Simpli-Dettes</h3>
                        
                        <div class="space-y-6 relative z-10">
                            @forelse($suggestedSettlements as $suggested)
                                <div class="p-5 rounded-[2rem] bg-white/[0.03] border border-white/5 hover:bg-white/[0.05] transition-all">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex -space-x-3">
                                                <div class="h-10 w-10 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-xs font-black text-rose-400" title="Doit payer">
                                                    {{ substr($suggested['debtor']->name, 0, 1) }}
                                                </div>
                                                <div class="h-10 w-10 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-xs font-black text-emerald-400 z-10" title="Reçoit le paiement">
                                                    {{ substr($suggested['creditor']->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-black text-white leading-tight">
                                                    <span class="text-rose-400 uppercase">{{ $suggested['debtor']->name }}</span>
                                                    <span class="text-slate-500 mx-1">→</span>
                                                    <span class="text-emerald-400 uppercase">{{ $suggested['creditor']->name }}</span>
                                                </p>
                                                <p class="text-[15px] font-black text-white tracking-tighter mt-1">{{ number_format($suggested['amount'], 2) }}€</p>
                                            </div>
                                        </div>

                                        @if(auth()->id() === $suggested['creditor']->id)
                                            <form action="{{ route('settlements.store', $colocation) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="debtor_id" value="{{ $suggested['debtor']->id }}">
                                                <input type="hidden" name="creditor_id" value="{{ $suggested['creditor']->id }}">
                                                <input type="hidden" name="amount" value="{{ $suggested['amount'] }}">
                                                <input type="hidden" name="month" value="{{ $month }}">
                                                <input type="hidden" name="year" value="{{ $year }}">
                                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-600/20">
                                                    Confirmé
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Tout est en ordre ! ✨</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div xl-glass class="p-10 rounded-[3rem] border border-white/5 bg-gradient-to-br from-fuchsia-600/10 to-transparent relative overflow-hidden">
                        <div class="absolute -top-12 -left-12 h-32 w-32 bg-fuchsia-500/10 rounded-full blur-3xl"></div>
                        <h3 class="text-xl font-black text-white tracking-tight mb-8 relative z-10 uppercase">Positions Actuelles</h3>
                        
                        <div class="space-y-6 relative z-10">
                            @foreach($balances as $balance)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 rounded-xl bg-slate-900 border border-white/5 flex items-center justify-center text-xs font-black text-white">
                                            {{ substr($balance['user']->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-white uppercase">{{ $balance['user']->name }}</p>
                                            <p class="text-[9px] font-bold text-slate-500">Payé: {{ number_format($balance['paid'], 2) }}€</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex flex-col items-end">
                                            <p class="text-sm font-black {{ $balance['balance'] >= 0.01 ? 'text-emerald-400' : ($balance['balance'] <= -0.01 ? 'text-rose-400' : 'text-slate-500') }}">
                                                {{ $balance['balance'] >= 0.01 ? '+' : '' }}{{ number_format($balance['balance'], 2) }}€
                                            </p>
                                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                                {{ $balance['balance'] >= 0.01 ? 'À RECEVOIR' : ($balance['balance'] <= -0.01 ? 'À PAYER' : 'ÉQUILIBRÉ') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 pt-8 border-t border-white/5">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-center italic">Calculé sur la base de {{ number_format($fairShare, 2) }}€ / personne</p>
                        </div>
                    </div>

                    <!-- Escouade (Compact) -->
                    <div xl-glass class="p-10 rounded-[3rem] border border-white/5">
                        <h3 class="text-xl font-black text-white tracking-tight mb-8">Escouade</h3>
                        <div class="space-y-6">
                            @foreach($colocation->members as $member)
                                <div class="flex items-center justify-between group/member">
                                    <div class="flex items-center space-x-4">
                                        <div class="relative">
                                            <div class="h-12 w-12 rounded-xl bg-slate-900 border border-white/10 flex items-center justify-center text-indigo-400 font-black shadow-inner group-hover/member:border-indigo-500/50 transition-all">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 h-3 w-3 rounded-full border-2 border-slate-950 {{ $member->pivot->role === 'owner' ? 'bg-amber-400' : 'bg-indigo-500' }}"></div>
                                        </div>
                                        <div>
                                            <p class="text-white font-black text-sm">{{ $member->name }}</p>
                                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">{{ $member->pivot->role === 'owner' ? 'Propriétaire' : 'Colocataire' }}</p>
                                        </div>
                                    </div>

                                    @if($isOwner && $member->id !== auth()->id())
                                        <form action="{{ route('colocations.removeMember', [$colocation, $member]) }}" method="POST" onsubmit="return confirm('Retirer ce membre de la colocation ?')">
                                            @csrf
                                            <button type="submit" class="p-2 text-slate-600 hover:text-rose-500 transition-colors opacity-0 group-hover/member:opacity-100">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-10 pt-8 border-t border-white/5 text-center">
                            <form action="{{ route('colocations.leave', $colocation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir quitter cette colocation ?')">
                                @csrf
                                <button type="submit" class="text-[10px] font-black text-slate-600 hover:text-rose-400 uppercase tracking-widest transition-colors flex items-center justify-center mx-auto space-x-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    <span>Quitter la colocation</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <!-- Modal Dépense (Déjà là) -->
        <div x-show="openExpenseModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
            <div xl-glass class="w-full max-w-lg p-10 rounded-[3rem] border border-white/10 shadow-2xl relative" @click.away="openExpenseModal = false">
                <button @click="openExpenseModal = false" class="absolute top-8 right-8 text-slate-500 hover:text-white"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                <h3 class="text-3xl font-black text-white tracking-tighter mb-8 uppercase">Nouvelle Dépense</h3>
                <form action="{{ route('depenses.store', $colocation) }}" method="POST" class="space-y-6">
                    @csrf
                    <div><x-input-label for="description" :value="__('DESCRIPTION')" /><x-text-input id="description" name="description" type="text" class="block w-full" required /></div>
                    <div class="grid grid-cols-2 gap-6">
                        <div><x-input-label for="amount" :value="__('MONTANT (€)')" /><x-text-input id="amount" name="amount" type="number" step="0.01" class="block w-full" required /></div>
                        <div><x-input-label for="date" :value="__('DATE')" /><x-text-input id="date" name="date" type="date" class="block w-full" value="{{ date('Y-m-d') }}" required /></div>
                    </div>
                    <div><x-input-label for="category_id" :value="__('CATÉGORIE')" /><select name="category_id" class="block w-full bg-slate-900/50 border-white/10 rounded-2xl text-slate-300 font-bold">@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
                    <div class="pt-4"><x-primary-button class="w-full py-4 uppercase font-black">ENREGISTRER</x-primary-button></div>
                </form>
            </div>
        </div>

        </div>
    </div>
</x-app-layout>
