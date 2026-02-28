<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter">
                Admin <span class="text-indigo-400">Control Center</span>
            </h2>
            <div class="flex items-center space-x-3 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/30">
                <span class="flex h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-300">Système Live</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{ openCategoryModal: false, openEditCategoryModal: false, editingCategory: {} }">
        <div class="max-w-7xl mx-auto space-y-10">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div xl-glass class="p-8 rounded-[2.5rem] border border-white/10 relative overflow-hidden group">
                    <div class="absolute -top-12 -right-12 h-32 w-32 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all"></div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Utilisateurs</p>
                    <h3 class="text-4xl font-black text-white">{{ $stats['total_users'] }}</h3>
                    <p class="text-[10px] font-bold text-indigo-400 mt-4 uppercase tracking-widest">Enregistrés globalement</p>
                </div>

                <!-- Total Colocations -->
                <div xl-glass class="p-8 rounded-[2.5rem] border border-white/10 relative overflow-hidden group">
                    <div class="absolute -top-12 -right-12 h-32 w-32 bg-fuchsia-500/10 rounded-full blur-3xl group-hover:bg-fuchsia-500/20 transition-all"></div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Colocations</p>
                    <h3 class="text-4xl font-black text-white">{{ $stats['total_colocations'] }}</h3>
                    <p class="text-[10px] font-bold text-fuchsia-400 mt-4 uppercase tracking-widest">Communautés actives</p>
                </div>

                <!-- Total Expenses -->
                <div xl-glass class="p-8 rounded-[2.5rem] border border-white/10 relative overflow-hidden group">
                    <div class="absolute -top-12 -right-12 h-32 w-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all"></div>
                    <p class="text-[10px) font-black text-slate-500 uppercase tracking-widest mb-2">Flux Financier</p>
                    <h3 class="text-4xl font-black text-white">{{ number_format($stats['total_expenses'], 2) }} €</h3>
                    <p class="text-[10px] font-bold text-emerald-400 mt-4 uppercase tracking-widest">Dépenses cumulées</p>
                </div>

                <!-- Banned Users -->
                <div xl-glass class="p-8 rounded-[2.5rem] border border-white/10 relative overflow-hidden group">
                    <div class="absolute -top-12 -right-12 h-32 w-32 bg-red-500/10 rounded-full blur-3xl group-hover:bg-red-500/20 transition-all"></div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Bannissements</p>
                    <h3 class="text-4xl font-black text-white">{{ $stats['banned_users'] }}</h3>
                    <p class="text-[10px] font-bold text-red-400 mt-4 uppercase tracking-widest">Utilisateurs exclus</p>
                </div>
            </div>

            <!-- Users Management Table -->
            <div xl-glass class="rounded-[3rem] border border-white/10 overflow-hidden">
                <div class="p-8 border-b border-white/10 flex items-center justify-between">
                    <h3 class="text-xl font-black text-white uppercase tracking-tighter">Gestion des Utilisateurs</h3>
                    <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        {{ $users->count() }} Comptes
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Utilisateur</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Rôle</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Réputation</th>
                                <th class="px-8 py-4 text-[10px) font-black text-slate-500 uppercase tracking-widest">Statut</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($users as $user)
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-10 w-10 rounded-xl bg-slate-800 flex items-center justify-center text-xs font-black border border-white/10">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-white font-black">{{ $user->name }}</p>
                                            <p class="text-[10px] text-slate-500 font-medium">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $user->role === 'admin' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30' : 'bg-slate-500/10 text-slate-400 border border-white/10' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-black {{ $user->reputation >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            {{ $user->reputation }}
                                        </span>
                                        <svg class="w-3 h-3 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($user->is_banned)
                                        <span class="px-2 py-1 rounded-md bg-red-500/20 text-red-400 text-[9px] font-black uppercase tracking-widest">BANNI</span>
                                    @else
                                        <span class="px-2 py-1 rounded-md bg-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-widest">ACTIF</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $user->is_banned ? 'bg-emerald-600 hover:bg-emerald-500 text-white' : 'bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white border border-red-500/30' }}">
                                                {{ $user->is_banned ? 'Réintégrer' : 'Bannir' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categories Management -->
            <div xl-glass class="rounded-[3rem] border border-white/10 overflow-hidden">
                <div class="p-8 border-b border-white/10 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter">Rubriques de Dépenses</h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Gérer les catégories globales</p>
                    </div>
                    <button @click="openCategoryModal = true" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 text-[10px] uppercase tracking-widest">
                        + NOUVELLE CATÉGORIE
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Nom</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Couleur</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Usage</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($categories as $category)
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-4 w-4 rounded-lg shadow-inner border border-white/5" style="background-color: {{ $category->color }};"></div>
                                        <p class="text-white font-black text-sm uppercase tracking-tight">{{ $category->name }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-4 w-4 rounded-full border border-white/10" style="background-color: {{ $category->color }};"></div>
                                        <code class="text-[10px] text-slate-500 font-mono">{{ strtoupper($category->color) }}</code>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        {{ $category->expenses_count ?? 0 }} DEPENSES
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        <button @click="editingCategory = {{ json_encode($category) }}; openEditCategoryModal = true" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-[10px] font-black text-indigo-400 uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition-all">
                                            Editer
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-[10px] font-black text-rose-500 uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                                                Supr.
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modals -->
        
        <!-- Modal Nouvelle Catégorie -->
        <div x-show="openCategoryModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
            <div xl-glass class="w-full max-w-lg p-10 rounded-[3rem] border border-white/10 shadow-2xl relative" @click.away="openCategoryModal = false">
                <button @click="openCategoryModal = false" class="absolute top-8 right-8 text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <h3 class="text-3xl font-black text-white tracking-tighter mb-8 uppercase">Nouvelle Catégorie</h3>
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('NOM')" />
                        <x-text-input id="name" name="name" type="text" class="block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="color" :value="__('COULEUR (HEX)')" />
                        <x-text-input id="color" name="color" type="color" class="block w-full h-11" value="#6366f1" required />
                    </div>
                    <div class="pt-4">
                        <x-primary-button class="w-full py-4 uppercase font-black">CRÉER LA RUBRIQUE</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Catégorie -->
        <div x-show="openEditCategoryModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
            <div xl-glass class="w-full max-w-lg p-10 rounded-[3rem] border border-white/10 shadow-2xl relative" @click.away="openEditCategoryModal = false">
                <button @click="openEditCategoryModal = false" class="absolute top-8 right-8 text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <h3 class="text-3xl font-black text-white tracking-tighter mb-8 uppercase">Modifier Catégorie</h3>
                <form :action="'{{ url('admin/categories') }}/' + editingCategory.id" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <div>
                        <x-input-label for="edit_name" :value="__('NOM')" />
                        <x-text-input id="edit_name" name="name" type="text" class="block w-full" x-model="editingCategory.name" required />
                    </div>
                    <div>
                        <x-input-label for="edit_color" :value="__('COULEUR (HEX)')" />
                        <x-text-input id="edit_color" name="color" type="color" class="block w-full h-11" x-model="editingCategory.color" required />
                    </div>
                    <div class="pt-4">
                        <x-primary-button class="w-full py-4 uppercase font-black">ENREGISTRER LES MODIFS</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
