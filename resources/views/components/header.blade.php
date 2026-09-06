@php
    
@endphp
<div class="nav-overlay" aria-hidden="true"></div>
<nav id="nav" aria-label="{{ app()->getLocale() === 'en' ? 'Main navigation' : 'Navigazione principale' }}">
    <a href="{{ url("/" . app()->getLocale() . "") }}" class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Associazione Pro Loco Pietrapertosana" />
        <span class="t">
            <b>Proloco Pietrapertosana</b>
            <small>Dolomiti Lucane</small>
        </span>
    </a>
    <div class="nav-right">
        <div class="links" id="links">
            
            <a href="{{ url("/" . app()->getLocale() . "") }}" class="{{ request()->is(app()->getLocale()) ? 'active' : '' }}">@lang('navigation.home')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi')) }}" class="{{ request()->routeIs("events.*", "traditions.*") ? 'active' : '' }}">@lang('navigation.events')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'community' : 'comunita')) }}" class="{{ request()->is("*/comunita") || request()->is("*/community") ? 'active' : '' }}">@lang('navigation.community')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'news' : 'notizie')) }}" class="{{ request()->routeIs("news.*") ? 'active' : '' }}">@lang('navigation.news')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'excellences' : 'eccellenze')) }}" class="{{ request()->is("*/eccellenze") || request()->is("*/excellences") ? 'active' : '' }}">@lang('navigation.excellences')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'tastes' : 'sapori')) }}" class="{{ request()->is("*/sapori") || request()->is("*/tastes") ? 'active' : '' }}">@lang('navigation.tastes')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'discover' : 'scopri')) }}" class="{{ request()->is("*/scopri") || request()->is("*/discover") ? 'active' : '' }}">@lang('navigation.discover')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'gallery' : 'galleria')) }}" class="{{ request()->is("*/galleria") || request()->is("*/gallery") ? 'active' : '' }}">@lang('navigation.gallery')</a>
            <a href="{{ url("/" . app()->getLocale() . "/pro-loco") }}" class="{{ request()->is("*/pro-loco") ? 'active' : '' }}">@lang('navigation.pro_loco')</a>
            
            <div class="lang-switcher">
                <a href="{{ localized_route(request()->path(), 'it') }}" class="lang-link {{ !(app()->getLocale() === 'en') ? 'active' : '' }}">IT</a>
                <span class="lang-sep">|</span>
                <a href="{{ localized_route(request()->path(), 'en') }}" class="lang-link {{ (app()->getLocale() === 'en') ? 'active' : '' }}">EN</a>
            </div>
        </div>

        <button class="hmb" id="hmb" type="button" aria-label="Menu" aria-expanded="false" aria-controls="links">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>
