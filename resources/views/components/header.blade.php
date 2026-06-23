@php
    
@endphp
<button
    class="nav-overlay"
    type="button"
    aria-label="{{ (app()->getLocale() === 'en') ? 'Close menu' : 'Chiudi menu' }}"
    onclick="document.body.classList.remove('mo')"
></button>
<nav id="nav">
    <a href="{{ url("/" . app()->getLocale() . "") }}" class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Associazione Pro Loco Pietrapertosana" />
        <span class="t">
            <b>Pietrapertosa</b>
            <small>Pro Loco &middot; Dolomiti Lucane</small>
        </span>
    </a>
    <div class="nav-right">
        <div class="links" id="links">
            <button class="drawer-close" aria-label="Chiudi menu" onclick="document.body.classList.remove('mo')">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            
            <a href="{{ url("/" . app()->getLocale() . "") }}" class="{{ request()->is(app()->getLocale()) ? 'active' : '' }}">@lang('navigation.home')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi')) }}" class="{{ request()->is("*/eventi") || request()->is("*/events") ? 'active' : '' }}">@lang('navigation.events')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'community' : 'comunita')) }}" class="{{ request()->is("*/comunita") || request()->is("*/community") ? 'active' : '' }}">@lang('navigation.community')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'news' : 'notizie')) }}" class="{{ request()->is("*/notizie") || request()->is("*/news") ? 'active' : '' }}">@lang('navigation.news')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'territory' : 'territorio')) }}" class="{{ request()->is("*/territorio") || request()->is("*/territory") ? 'active' : '' }}">@lang('navigation.territory')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'tastes' : 'sapori')) }}" class="{{ request()->is("*/sapori") || request()->is("*/tastes") ? 'active' : '' }}">@lang('navigation.tastes')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'discover' : 'scopri')) }}" class="{{ request()->is("*/scopri") || request()->is("*/discover") ? 'active' : '' }}">@lang('navigation.discover')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'gallery' : 'galleria')) }}" class="{{ request()->is("*/galleria") || request()->is("*/gallery") ? 'active' : '' }}">@lang('navigation.gallery')</a>
            <a href="{{ url("/" . app()->getLocale() . "/pro-loco") }}" class="{{ request()->is("*/pro-loco") ? 'active' : '' }}">@lang('navigation.pro_loco')</a>
            
            <div class="lang-switcher">
                @php
                    $path = request()->path();
                    $segments = explode('/', $path);
                    array_shift($segments);
                    $rest = implode('/', $segments);
                @endphp
                <a href="{{ url('/it' . ($rest ? '/' . $rest : '')) }}" class="lang-link {{ !(app()->getLocale() === 'en') ? 'active' : '' }}">IT</a>
                <span class="lang-sep">|</span>
                <a href="{{ url('/en' . ($rest ? '/' . $rest : '')) }}" class="lang-link {{ (app()->getLocale() === 'en') ? 'active' : '' }}">EN</a>
            </div>
        </div>

        <button class="hmb" id="hmb" aria-label="Menu" onclick="document.body.classList.toggle('mo')">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>

<script>
    window.addEventListener('scroll', () => {
        if(window.scrollY > 50) {
            document.getElementById('nav').classList.add('sc');
        } else {
            document.getElementById('nav').classList.remove('sc');
        }
    });
</script>
