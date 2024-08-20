<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{Auth::user()->dark ? 'dark' : ''}}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/index.global.min.js"></script>
        <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
        <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
        <script src="{{asset('js/fontawesome.js')}}"></script>

        <script>
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const URL_API = "{{url('/')}}";
            const USER_LANG = "{{ Auth()->user()->language }}";


        </script>


        @stack('utils')

        @laravelPWA
        <!-- Styles -->
        @livewireStyles


    </head>
    <body class="font-sans antialiased">

    @php
        if (file_exists(resource_path('../lang/'.Auth::user()->language.'.json'))) {
            $translationJSON = file_get_contents(resource_path('../lang/'.Auth::user()->language.'.json'));
        } else {
            $translationJSON = "{}";
        }
    @endphp


    <script>

        const translations = @php echo $translationJSON;  @endphp;

        const trans = (key) => {
            return translations[key] || key;
        };
    </script>

    <nav class="sticky top-0 z-40 max-h-16 h-16 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button id="sidebar-open-button" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 sm:hidden">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="{{route('dashboard')}}" class="flex ml-2 md:mr-24">
                        <img src="{{asset('/images/logo.png')}}" class="h-8 mr-3" alt="FlowBite Logo" />

                    </a>

                </div>
                <div class="flex items-center">
                    <div class="flex items-center ml-3">
                        <div class="sm:flex sm:items-center sm:ml-6">
                            <!-- Teams Dropdown -->
                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                <div class="ml-3 relative">
                                    <x-dropdown align="right" width="60">
                                        <x-slot name="trigger">
                                            <span class="inline-flex rounded-md">
                                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                                    {{ Auth::user()->currentTeam->name }}

                                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </x-slot>

                                        <x-slot name="content">
                                            <div class="w-60">
                                                <!-- Team Management -->
                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    {{ __('Manage Team') }}
                                                </div>

                                                <!-- Team Settings -->
                                                <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                                    {{ __('Team Settings') }}
                                                </x-dropdown-link>

                                                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                                    <x-dropdown-link href="{{ route('teams.create') }}">
                                                        {{ __('Create New Team') }}
                                                    </x-dropdown-link>
                                                @endcan

                                                <!-- Team Switcher -->
                                                @if (Auth::user()->allTeams()->count() > 1)
                                                    <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                                        {{ __('Switch Teams') }}
                                                    </div>

                                                    @foreach (Auth::user()->allTeams() as $team)
                                                        <x-switchable-team :team="$team" />
                                                    @endforeach
                                                @endif
                                            </div>
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            @endif

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                            </button>
                                        @else
                                            <span class="inline-flex rounded-md">
                                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                                    {{ Auth::user()->name }}

                                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                    </svg>
                                                </button>
                                            </span>
                                        @endif
                                    </x-slot>

                                    <x-slot name="content">
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Manage Account') }}
                                        </div>

                                        <x-dropdown-link href="{{ route('profile.show') }}">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                            <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                                {{ __('API Tokens') }}
                                            </x-dropdown-link>
                                        @endif

                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}" x-data>
                                            @csrf

                                            <x-dropdown-link href="{{ route('logout') }}"
                                                    @click.prevent="$root.submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    @php

    use App\Models\Comunidad;
    $comunidad = Comunidad::find(Session::get('comunidad'));
    @endphp

    <div class="flex overflow-hidden relative" style="max-height: calc(100vh - 4rem);">

        {{-- Sidebar lateral --}}
        <aside id="logo-sidebar" style="height: calc(100vh - 4rem);" class="fixed z-40 left-0 top-16 h-full transition-transform -translate-x-full  bg-white border-r border-gray-200 w-52 sm:relative sm:translate-x-0 sm:top-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
            <div class="h-full w-52 px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800" style="display:flex;">
                <ul class="space-y-2 font-medium" style="display: flex;
                flex-wrap: wrap;
                justify-content: center;
                width: 100%;">

                    <div class="sidebar">

                        @if(count(Auth::user()->comunidades)>1)
                            <li>
                                <a href="{{ route('menu') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fa-solid fa-users w-5 text-center" style="color: #19a8d9;"></i>
                                    <span class="ml-3">{{__("My Communities")}}</span>
                                </a>
                            </li>
                        @endif
                        @if ($comunidad != null)
                                <a href="{{route('submenu', 2)}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fa-solid fa-building w-5 text-center" style="color: #19a8d9;"></i>
                                    <span class="ml-3">{{__("Community Menu")}}</span>
                                </a>

                            </li>

                            <li>
                                <a href="{{route('submenu', 1)}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fa-solid fa-people-roof w-5 text-center" style="color: #19a8d9;"></i>
                                    <span class="ml-3">{{__("Neighbors Menu")}}</span>
                                </a>
                            </li>

                            @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)
                                <li>
                                    <a href="{{route('submenu', 3)}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fa-sharp fa-solid fa-gears w-5 text-center" style="color: #19a8d9;"></i>

                                        <span class="ml-3">{{__("Management")}}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if(Auth::user()->super_admin)
                            <li>
                                <a href="{{route('admin_view')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700" >
                                    <i class="fa-solid fa-folder-gear w-5 text-center" style="color: #246a9c;"></i>
                                    <span class="ml-3">Admin</span>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a id="install-button" style="display: none" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"  >
                                <i class="fa-solid fa-download w-5 text-center" style="color: #246a9c;"></i>

                                <button class="ml-3">{{__("Install")}}</button>
                            </a>

                        </li>

                    </div>
                    @if (Session::has('comunidad'))
                        <div class="sidebar2 flex flex-row items-end">
                            <div class="flex felx-row m-1 rounded bg-slate-100 p-1 justify-between items-center gap-2">

                                <img src="{{asset('/storage/uploads/comunidades/img/'.Session::get('comunidad').'.png')}}" class="w-2/5 rounded-md aspect-square object-cover" onerror="this.src='{{asset('/images/logo.png')}}'"/>

                                <p class="w-3/5 overflow-hidden">{{$comunidad->nombre}}</p>
                            </div>
                        </div>
                    @endif
                </ul>
            </div>
        </aside>

        {{-- Sidebar responsive --}}
        <div id="bottom-sidebar" class="absolute bottom-0 w-full h-[101px] bg-white z-50 shadow-[0_2px_5px_0px_rgba(0,0,0,0.25)] flex justify-between items-center px-5 text-[2.5rem] text-[#1E3050] dark:bg-[#1E3050] opacity-0">
            @if(count(Auth::user()->comunidades)>1)
                @if ($comunidad == null)
                    <div class="h-[70px] border-l-[2px] boder-[#000000]/.3"></div>
                @endif

                <a href="{{ route('menu') }}" class="flex items-center p-2 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-users w-[4.25rem] text-center"></i>
                </a>

                <div class="h-[70px] border-l-[2px] boder-[#000000]/.3"></div>
            @else
                @if ($comunidad == null)
                    <div class="h-[70px] border-l-[2px] boder-[#000000]/.3"></div>
                @endif

                <a href="{{ route('comunidad_vista') }}" class="flex items-center p-2 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-users w-[4.25rem] text-center"></i>
                </a>

                <div class="h-[70px] border-l-[2px] boder-[#000000]/.3"></div>
            @endif
            @if ($comunidad != null)
                <a href="{{route('submenu', 2)}}" class="flex items-center p-2 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-building w-[4.25rem] text-center"></i>
                </a>

                <div class="h-[70px] border-l-[2px] boder-[#000000]/.3"></div>

                <a href="{{route('submenu', 1)}}" class="flex items-center p-2 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-people-roof w-[4.25rem] text-center"></i>
                </a>

                @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)
                    <div class="h-[70px] border-l-[2px] boder-[#000000]/.3"></div>

                    <a href="{{route('submenu', 3)}}" class="flex items-center p-2 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fa-sharp fa-solid fa-gears w-[4.25rem] text-center"></i>
                    </a>
                @endif
            @endif
        </div>

        <div style="min-height: calc(100vh - 4rem);" class="p-4 dark:bg-gray-600 bg-slate-100 grow overflow-auto main-container">
            <main class="h-full relative">
                {{ $slot }}
            </main>
        </div>

    </div>

    {{-- Responsive de la sidebar --}}
    <style>
        /* No permitir que se muestre el boton de abrir la sidebar */
        #sidebar-open-button {
            display: none;
        }

        /* Esconder la botom sidebar por defecto */
        #bottom-sidebar {
            display: none;
            opacity: 1;
        }

        /* Esconder la sidebar en responsive */
        @media (max-width: 640px) {
            #logo-sidebar {
                display: none
            }

            #bottom-sidebar {
                display: flex;
            }
        }
    </style>

    @stack('modals')


    <script>
        window.addEventListener('beforeinstallprompt', (event) => {
            event.preventDefault();
            const installButton = document.getElementById('install-button');
            installButton.style.display = 'block';
            installButton.addEventListener('click', () => {
            event.prompt();
            });
        });

        window.addEventListener('appinstalled', (event) => {
            const installButton = document.getElementById('install-button');
            installButton.style.display = 'none';
        });

        const darkMode=()=>{
            // if(dr){
                document.documentElement.classList.toggle('dark');

            // }else{
            //     document.documentElement.classList.remove('dark');
            // }
        }
    </script>
    @livewireScripts
    </body>
</html>
