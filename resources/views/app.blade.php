<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BMIID') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
        <style>
            body{
                max-width:1400px !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100 mx-auto">
        @inertia
    <script>
        const case_min_year = {{ \App\Models\CasesModel::minYear() }};
        
        const case_years = (function() {
            const length = (new Date()).getFullYear() - case_min_year + 1;
            return Array.from({ length }, (_, i) => case_min_year + i);
        })();
        
        @if (session('screen_msg'))
            alert('{{ session('screen_msg') }}');
        @endif
    </script>
    </body>
</html>
