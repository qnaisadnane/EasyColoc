<x-app-layout>
    <x-slot name="header">
        <div class="relative py-4">
            <div class="absolute -left-4 top-0 h-16 w-1 bg-fuchsia-500 rounded-full shadow-[0_0_20px_rgba(217,70,239,0.5)]"></div>
            <h2 class="font-black text-5xl text-white tracking-tighter">
                Bon retour, {{ Auth::user()->name }}.
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Main Welcome Island -->
                <div class="md:col-span-2 space-y-10">
                    <div xl-glass class="p-10 rounded-[3rem] relative overflow-hidden group border border-white/10">
                        <div class="absolute -top-24 -right-24 h-64 w-64 bg-indigo-600/20 rounded-full blur-[100px] animate-pulse"></div>
                        
                        <div class="relative z-10">
                            <h3 class="text-3xl font-black text-white mb-6">Gerer les colocations</h3>
                            <p class="text-slate-400 text-lg font-medium max-w-lg mb-12">Gerez vos colocations, suivez vos dépenses et collaborez avec vos colocataires dans l'interface la plus avancée jamais conçue.</p>
                            
                            <div class="flex flex-wrap gap-6">
                                <a href="{{ route('colocations.index') }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 text-sm tracking-widest uppercase">
                                    MES COLOCATIONS
                                </a>
                                <a href="{{ route('profile.edit') }}" class="px-8 py-4 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/10 rounded-2xl transition-all active:scale-95 text-sm tracking-widest uppercase">
                                    CONFIGURER PROFIL
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- News/Updates Island -->
                    <div xl-glass class="p-8 rounded-[2.5rem] border border-white/5">
                        <h4 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-8">Journal d'activité</h4>
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="h-16 w-16 rounded-2xl bg-white/5 flex items-center justify-center text-slate-600 mb-6">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-slate-500 font-bold uppercase tracking-tighter">Tout est calme dans la nébuleuse.</p>
                        </div>
                    </div>
                </div>

                <!-- Profile/Stats Sidebar -->
                <div class="space-y-10">
                    <div xl-glass class="p-8 rounded-[2.5rem] bg-gradient-to-br from-fuchsia-600/10 to-transparent border border-white/5">
                        <div class="flex flex-col items-center text-center">
                            <div class="h-24 w-24 rounded-[2rem] bg-gradient-to-br from-indigo-500 to-fuchsia-500 p-1 mb-6 shadow-2xl shadow-fuchsia-500/20">
                                <div class="h-full w-full rounded-[1.8rem] bg-slate-950 flex items-center justify-center text-3xl font-black text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-white tracking-tight">{{ Auth::user()->name }}</h3>
                            <p class="text-fuchsia-400 text-[10px] font-black uppercase tracking-widest mt-2">Utilisateur Vérifié</p>
                        </div>
                        
                        <div class="mt-10 pt-10 border-t border-white/5 grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-black text-white">0</p>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">Colos</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-black text-white">€0</p>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">Dépenses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
