<nav x-data="{ open: false }" class="bg-dark dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 container-fluid">


    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center justify-content-start">
                    <a href="{{ route('novelas.index') }}">
                        <x-application-logo class="block text-bg-light h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <h1 style="color:white; margin:auto; margin-left:10px; font-family:'MedievalSharp'">DreamPen</h1>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex justify-content-between">
                
                   
                        <button class="btn btn-light" style="height: 35px; margin:auto; margin-left:20px;">
                        <x-nav-link :href="route('novelas.index')" :active="request()->routeIs('novelas.index')">
                        {{ __('Lista de novelas') }}
                        </x-nav-link>
                    </button>
                  
                    
                       
                    
                    <button class="btn btn-light" style="height: 35px; margin:auto; margin-left:20px;">
                    <x-nav-link :href="route('novela.show', $idnovAzar->random())" :active="request()->routeIs('novela.show', $idnovAzar->random())">
                        {{ __('Novela sorpresa') }}
                    </x-nav-link>
                    </button>
                    

                    
                    
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
            @auth
                <x-dropdown : width="48">
                
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                          
                        
                        
                        <img src="{{ asset('storage/assets/'.Auth::user()->link_imagen) }}" alt="" style="width:25px;height:25px;">

        
            
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Configuración del perfil') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.show', Auth::user()->id)">
                            {{ __('Mi perfil') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mis novelas') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mis favoritos') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>

                </x-dropdown>
                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Iniciar sesión
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Registrarse
                                        </a>
                                    @endif
                                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('novelas.index')" :active="request()->routeIs('novelas.index')" class="btn btn-light">
                {{ __('Lista de novelas') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('novela.show', $idnovAzar->random())" :active="request()->routeIs('novela.show', $idnovAzar->random())" class="btn btn-light">
                {{ __('Novela sorpresa') }}
            </x-responsive-nav-link>

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
        @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Mis novelas') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Mis favoritos') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>

                 @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        style="color:white;"
                                        >
                                        Iniciar sesión
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Registrarse
                                        </a>
                                    @endif
                                @endauth

            </div>
        </div>
    </div>
</nav>
