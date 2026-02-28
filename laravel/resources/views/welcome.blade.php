<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EasyColoc • La Colocation Intelligente</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .crystal-gradient {
                background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.15) 0%, rgba(168, 85, 247, 0.1) 50%, transparent 100%);
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .neon-text-indigo { text-shadow: 0 0 20px rgba(99, 102, 241, 0.5); }
            .neon-text-fuchsia { text-shadow: 0 0 20px rgba(217, 70, 239, 0.5); }
        </style>
    </head>
    <body class="bg-[#020617] text-white selection:bg-indigo-500/30 overflow-x-hidden">
        <!-- Hero Background -->
        <div class="fixed inset-0 z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-600/20 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-fuchsia-600/20 blur-[120px] rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full crystal-gradient"></div>
        </div>

        <!-- Navigation -->
        <nav class="relative z-50 flex items-center justify-between px-8 py-8 max-w-7xl mx-auto">
            <div class="flex items-center space-x-3 group cursor-pointer">
                <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-[0_0_30px_-5px_rgba(99,102,241,0.6)] group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span class="text-xl font-black tracking-tighter uppercase whitespace-nowrap">Easy<span class="text-indigo-400">Coloc</span></span>
            </div>

            <div class="flex items-center space-x-8">
                @if (Route::has('login'))
                    <div class="flex items-center space-x-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Tableau de bord</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-black text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-all active:scale-95 shadow-xl shadow-white/10">
                                    Commencer
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <!-- Main Hero -->
        <main class="relative z-10 pt-20 pb-40 px-8">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="space-y-10">
                    <div class="inline-flex items-center space-x-3 px-4 py-2 rounded-full bg-white/5 border border-white/10">
                        <span class="flex h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300">Nouveau : Gestion des balances</span>
                    </div>

                    <h1 class="text-7xl lg:text-8xl font-black leading-[0.9] tracking-tighter text-white">
                        Vivez à plusieurs, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-fuchsia-400 neon-text-indigo">sans le stress.</span>
                    </h1>

                    <p class="text-xl text-slate-400 max-w-xl font-medium leading-relaxed">
                        EasyColoc centralise vos dépenses dans une interface crystal-clear. Équilibrez les comptes instantanément.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 pt-6">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl transition-all shadow-[0_0_50px_-10px_rgba(99,102,241,0.5)] active:scale-95 uppercase tracking-widest text-sm">
                            Créer ma coloc
                        </a>
                        <a href="#features" class="w-full sm:w-auto px-10 py-5 bg-white/5 border border-white/10 hover:bg-white/10 text-white font-black rounded-2xl transition-all active:scale-95 uppercase tracking-widest text-sm">
                            Voir les fonctionnalités
                        </a>
                    </div>

                    <div class="pt-10 flex items-center space-x-8">
                        <div class="flex -space-x-3">
                            @for($i=1; $i<=4; $i++)
                                <div class="h-10 w-10 rounded-full border-2 border-[#020617] bg-slate-800 flex items-center justify-center text-[10px] font-bold">
                                    {{ chr(64 + $i) }}
                                </div>
                            @endfor
                        </div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                            Rejoint par <span class="text-white">+2000 colocations</span> en France
                        </p>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-10 bg-gradient-to-tr from-indigo-500/20 to-fuchsia-500/20 blur-[80px] rounded-[5rem] group-hover:opacity-100 transition-opacity duration-700 opacity-60"></div>
                    <div class="relative glass-card aspect-square rounded-[4rem] overflow-hidden border border-white/10 shadow-2xl flex items-center justify-center">
                         <img src="/easy_coloc_hero_preview_1772185825007.png" alt="EasyColoc Preview" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section id="features" class="relative z-10 py-40 px-8 bg-slate-950/50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-32 space-y-6">
                    <h2 class="text-5xl font-black tracking-tight text-white uppercase">Tout ce dont vous avez besoin</h2>
                    <p class="text-slate-500 text-lg max-w-2xl mx-auto font-medium">Une suite d'outils pensée pour la vie à plusieurs et le règlement des dépenses.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Feature 1 -->
                    <div class="glass-card p-10 rounded-[3rem] hover:translate-y-[-10px] transition-all duration-500">
                        <div class="h-14 w-14 rounded-2xl bg-indigo-600/20 flex items-center justify-center text-indigo-400 mb-8 border border-indigo-500/30">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-2xl font-black text-white mb-4 uppercase tracking-tight">Comptes Justes</h4>
                        <p class="text-slate-500 font-medium">Fini les calculs interminables. Saisissez vos dépenses et laissez EasyColoc équilibrer les balances en temps réel.</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 py-20 px-8 border-t border-white/5">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
                <div class="flex items-center space-x-3">
                    <span class="text-lg font-black tracking-tighter uppercase whitespace-nowrap text-slate-500">Easy<span class="text-indigo-400/50">Coloc</span></span>
                </div>
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">&copy; 2026 EasyColoc Inc. Façonné avec soin pour les colocs.</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-xs font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Twitter</a>
                    <a href="#" class="text-xs font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">GitHub</a>
                </div>
            </div>
        </footer>
    </body>
</html>
