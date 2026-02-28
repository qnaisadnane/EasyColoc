<div class="pt-6 relative z-50">
    <nav x-data="{ open: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div xl-glass class="rounded-[2rem] border border-white/5 shadow-2xl shadow-black/50">
            <div class="px-6 sm:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center group">
                                <div class="h-10 w-10 bg-gradient-to-tr from-indigo-600 to-fuchsia-600 rounded-xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300">
                                    <x-application-logo class="h-6 w-auto fill-current text-white" />
                                </div>
                                <span class="ml-3 text-xl font-black text-white tracking-tighter group-hover:text-indigo-400 transition-colors">EasyColoc</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-2 sm:ms-12 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Tableau de bord') }}
                            </x-nav-link>
                            <x-nav-link :href="route('colocations.index')" :active="request()->routeIs('colocations.*')">
                                {{ __('Mes Colocations') }}
                            </x-nav-link>
                            @if(Auth::user()->role === 'admin')
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-indigo-400">
                                    {{ __('Admin') }}
                                </x-nav-link>
                            @endif
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center px-4 py-2 rounded-2xl bg-white/5 border border-white/10 text-sm font-bold text-slate-300 hover:text-white hover:bg-white/10 hover:border-indigo-500/30 transition-all duration-300">
                                    <div class="h-6 w-6 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-[10px] text-white mr-2">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }}
                                    <svg class="ms-1 h-4 w-4 opacity-40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="xl-glass border-white/10 rounded-2xl overflow-hidden mt-2 p-1">
                                    <x-dropdown-link :href="route('profile.edit')" class="rounded-xl text-slate-300 hover:bg-white/5 hover:text-indigo-400">
                                        {{ __('Mon Profil') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();"
                                                class="rounded-xl text-red-400 hover:bg-red-500/10">
                                            {{ __('Déconnexion') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="p-3 rounded-2xl bg-white/5 text-slate-400 hover:text-indigo-400 transition-colors">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/5 bg-slate-900/50 backdrop-blur-xl">
                <div class="pt-4 pb-6 px-6 space-y-2">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Tableau de bord') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('colocations.index')" :active="request()->routeIs('colocations.*')">
                        {{ __('Mes Colocations') }}
                    </x-responsive-nav-link>
                    @if(Auth::user()->role === 'admin')
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-indigo-400">
                            {{ __('Administration') }}
                        </x-responsive-nav-link>
                    @endif
                    <div class="pt-4 mt-4 border-t border-white/5">
                        <div class="flex items-center px-2">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="ms-3">
                                <div class="font-bold text-white">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Profil') }}
                            </x-responsive-nav-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-400">
                                    {{ __('Sortie') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
