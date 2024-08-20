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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{asset('js/fontawesome.js')}}"></script>
        @laravelPWA
        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="h-full dark:bg-gray-600 bg-slate-100">
            <main class="h-full flex justify-center relative">
                {{ $slot }}
            </main>
        </div>


        @stack('modals')

        @livewireScripts
        <script>
            switch_1.addEventListener('change', function(){
                darkMode()
            })
            const darkMode=()=>{
                // if(dr){
                    document.documentElement.classList.toggle('dark');

                // }else{
                //     document.documentElement.classList.remove('dark');
                // }
            }
        </script>
    </body>
</html>
