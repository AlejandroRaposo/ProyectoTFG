<nav x-data="{ open: false }" class="bg-dark dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 w-100 mt-auto">


    <!-- Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
               
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex justify-content-between">
                
                   
                        
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">
                        {{ __('Política de cookies') }}
                        </x-nav-link>
                   
                  
                    
                    
                    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">
                        {{ __('Aviso legal') }}
                    </x-nav-link>
                   
                    

                    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">
                        {{ __('Política de privacidad') }}
                    </x-nav-link>
                   
                    
                </div>

                
            </div>
            <div class="d-flex flex-row-reverse align-items-center justify-content-evenly " >
            <a href=""><img src="{{ asset('storage/assets/'.'x-twitter.svg') }}" alt="" class="rounded-circle" style="width:50px;height:50px; background-color:white; padding:5px; margin-left:10px;"></a>
            <a href=""><img src="{{ asset('storage/assets/'.'discord.svg') }}" alt="" class="rounded-circle" style="width:50px;height:50px; background-color:white; padding:5px;"></a>
                </div>

            <!-- -->

            

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden dropup">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out  dropup">
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
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class=" text-white ">
                {{ __('Política de cookies') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class=" text-white ">
                {{ __('Aviso legal') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class=" text-white ">
                {{ __('Política de privacidad') }}
            </x-responsive-nav-link>
        </div>

 
            </div>
        </div>
    </div>
</nav>
