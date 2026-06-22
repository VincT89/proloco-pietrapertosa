<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @hasSection('title')
            @yield('title')
        @else
            Pietrapertosa - Sospesi tra cielo e pietra - Pro Loco
        @endif
    </title>
    <meta name="description" content="@yield('seo_description', 'Associazione Pro Loco Pietrapertosana: il borgo più alto della Basilicata, tra le guglie delle Dolomiti Lucane. Castello saraceno, Arabata, Volo dell\'Angelo, news, eventi, locandine e foto.')">

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="ready">
    @include('components.header')
    
    @yield('content')

    @include('components.footer')
    @include('components.lightbox')
    @include('components.cookie-consent')
</body>
</html>
