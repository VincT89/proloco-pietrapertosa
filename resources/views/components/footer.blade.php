@php
    
@endphp
<footer>
    <div class="f-big" id="fBig">
        Pietra<em>pertosa</em>
    </div>

    <div class="f-cols">
        <div class="fb footer-brand-box">
            <img src="{{ asset('images/logo.png') }}" alt="Pro Loco Pietrapertosa Logo" class="footer-logo" />
            <div>
                <b>Pro Loco Pietrapertosana</b>
                <ul class="fu">
                    <li><a href="mailto:prolocopietrapertosa@gmail.com">prolocopietrapertosa@gmail.com</a></li>
                    <li><a href="mailto:prolocopietrapertosa@pec.it">prolocopietrapertosa@pec.it</a></li>
                    <li>342 989 6770</li>
                </ul>
                <p>@lang('navigation.association_desc')</p>
                <div class="footer-address">
                    Via della Speranza, 159<br/>
                    85010 Pietrapertosa (PZ)<br/>
                    P.Iva: 01925320762<br/>
                    Cod. Fiscale: 96028030763
                </div>
                
                <div class="footer-admin-link-wrapper">
                    <a href="{{ url('/admin/login') }}" class="adm-link footer-admin-link" target="_blank" rel="noopener noreferrer">
                        <svg class="footer-admin-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        @lang('navigation.restricted_access')
                    </a>
                </div>
            </div>
        </div>

        <div>
            <h5>@lang('navigation.explore')</h5>
            <a href="{{ url("/" . app()->getLocale() . "") }}">@lang('navigation.home')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi')) }}">@lang('navigation.events')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'community' : 'comunita')) }}">@lang('navigation.community')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'excellences' : 'eccellenze')) }}">@lang('navigation.excellences')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'tastes' : 'sapori')) }}">@lang('navigation.tastes')</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'discover' : 'scopri')) }}">@lang('navigation.discover')</a>
            <a href="{{ url("/" . app()->getLocale() . "/pro-loco") }}">@lang('navigation.pro_loco')</a>
        </div>

        <div>
            <h5>@lang('navigation.excellences')</h5>
            <a href="https://www.volodellangelo.com" target="_blank" rel="noopener noreferrer">Volo dell'Angelo</a>
            <a href="https://www.parcogallipolicognato.it/comuni_dettaglio.php?id=76061" target="_blank" rel="noopener noreferrer">Parco Gallipoli Cognato</a>
            <a href="https://www.basilicataturistica.it/territori/pietrapertosa/" target="_blank" rel="noopener noreferrer">APT Basilicata</a>
            <a href="https://borghipiubelliditalia.it/borgo/pietrapertosa/" target="_blank" rel="noopener noreferrer">Borghi più belli d'Italia</a>
        </div>

        <div>
            <h5>@lang('navigation.follow_us')</h5>
            <a href="https://www.facebook.com/prolocopietrapertosa1/?locale=it_IT" target="_blank" rel="noopener noreferrer">Facebook</a>
            <a href="https://www.instagram.com/proloco_pietrapertosa/" target="_blank" rel="noopener noreferrer">Instagram</a>
            <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'gallery' : 'galleria')) }}">Il nostro archivio</a>
        </div>
    </div>

    <div class="f-bot">
        <span>© 2026 Pro Loco Pietrapertosa</span>
        <span class="f-bot-links">
            <a href="{{ url('/' . app()->getLocale() . '/privacy') }}">Privacy Policy</a>
            <a href="{{ url('/' . app()->getLocale() . '/' . ((app()->getLocale() === 'en') ? 'cookies' : 'cookie')) }}">Cookie Policy</a>
            <a href="{{ localized_route(request()->path(), app()->getLocale()) === url('/') ? url('/' . app()->getLocale() . '/' . ((app()->getLocale() === 'en') ? 'photo-thanks' : 'ringraziamenti-fotografici')) : url('/' . app()->getLocale() . '/' . ((app()->getLocale() === 'en') ? 'photo-thanks' : 'ringraziamenti-fotografici')) }}">
                {{ app()->getLocale() === 'it' ? 'Ringraziamenti fotografici' : 'Photo acknowledgements' }}
            </a>
        </span>
        <span>
            <i class="footer-credit">@lang('navigation.made_with_heart')</i>
        </span>
    </div>
</footer>
