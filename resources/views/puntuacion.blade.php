<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="bodyPersonalizadoPuntuacion">
    <header class="p-3 bg-dark text-white">
        <div class="text-end">
            @if(session()->exists('userLogin') && session('userLogin')==true && (session('rol')=='Analista' || session('rol')=='Jefe'))
                <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-warning">Logout</button>              
            @endif
        </div>
        
        <form id="logout-form" action="/login/logout" method="POST" class="d-none">
            
        </form>

    </header>
    {{-- <div class="row" id="body-row"> --}}
        
        {{-- MAIN --}}
        <div id="app">
            <puntuacion-component></puntuacion-component>
        </div>
{{--     
    </div><!-- body-row END --> --}}
</body>

</html>


    

