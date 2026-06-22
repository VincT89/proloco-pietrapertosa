<div id="cookieBanner" class="cookie-banner is-hidden">
    <div class="cookie-banner-inner">
        <h4 class="cookie-banner-title">{{ __('legal.cookie_banner_title') }}</h4>
        <p class="cookie-banner-text">{{ __('legal.cookie_banner_text') }} <a href="{{ url('/' . app()->getLocale() . '/cookie') }}" class="cookie-link">{{ __('legal.cookie') }}</a></p>
        <div class="cookie-actions">
            <button class="cookie-btn cookie-btn-accept" id="cookieAcceptBtn">{{ __('legal.accept_external') }}</button>
            <button class="cookie-btn cookie-btn-reject" id="cookieRejectBtn">{{ __('legal.reject_external') }}</button>
        </div>
    </div>
</div>
