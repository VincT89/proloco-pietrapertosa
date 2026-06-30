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
    
    {!! \App\Services\SeoService::generateTags() !!}

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="ready">
    @include('components.header')
    
    @yield('content')

    @include('components.footer')
    @include('components.lightbox')
    @include('components.cookie-consent')
    @include('components.chatbot')
</body>
</html>
