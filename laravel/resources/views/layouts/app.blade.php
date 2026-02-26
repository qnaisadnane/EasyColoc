<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [xl-glass] {
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(24px) saturate(180%);
                -webkit-backdrop-filter: blur(24px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
            .neon-glow {
                box-shadow: 0 0 20px -5px rgba(99, 102, 241, 0.5);
            }
            
            /* Fluid Mesh Gradient Animation */
            @keyframes float {
                0% { transform: translate(0, 0) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0, 0) scale(1); }
            }
            .mesh-blob {
                position: fixed;
                width: 70vw;
                height: 70vw;
                border-radius: 50%;
                filter: blur(120px);
                z-index: -1;
                opacity: 0.2;
                pointer-events: none;
                animation: float 20s infinite ease-in-out;
            }
            .noise-overlay {
                position: fixed;
                inset: 0;
                z-index: -1;
                opacity: 0.03;
                pointer-events: none;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3%3Cfilter id='noiseFilter'%3%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3%3C/filter%3%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3%3C/svg%3");
            }
        </style>
    </head>
    <body class="font-['Plus_Jakarta_Sans'] antialiased text-slate-200 bg-slate-950 selection:bg-indigo-500/30 selection:text-indigo-200 overflow-x-hidden">
        <!-- Fluid Mesh Background -->
        <div class="mesh-blob bg-indigo-600 top-[-20%] left-[-10%]"></div>
        <div class="mesh-blob bg-fuchsia-600 bottom-[-20%] right-[-10%]" style="animation-delay: -5s; animation-duration: 25s"></div>
        <div class="mesh-blob bg-teal-500 top-[20%] right-[-20%] w-[50vw] h-[50vw] opacity-0.1" style="animation-delay: -10s; animation-duration: 18s"></div>
        <div class="noise-overlay"></div>

        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="relative z-10 pt-8 pb-4">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-10">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="xl-glass border-green-500/20 text-green-400 px-6 py-4 rounded-2xl mb-6 flex items-center shadow-xl shadow-green-950/20" role="alert">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="xl-glass border-red-500/20 text-red-400 px-6 py-4 rounded-2xl mb-6 flex items-center shadow-xl shadow-red-950/20" role="alert">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
