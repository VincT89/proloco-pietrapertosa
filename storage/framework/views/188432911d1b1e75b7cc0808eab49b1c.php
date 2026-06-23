<?php
    
?>
<button
    class="nav-overlay"
    type="button"
    aria-label="<?php echo e((app()->getLocale() === 'en') ? 'Close menu' : 'Chiudi menu'); ?>"
    onclick="document.body.classList.remove('mo')"
></button>
<nav id="nav">
    <a href="<?php echo e(url("/" . app()->getLocale() . "")); ?>" class="brand">
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo Associazione Pro Loco Pietrapertosana" />
        <span class="t">
            <b>Proloco Pietrapertosana</b>
            <small>Dolomiti Lucane</small>
        </span>
    </a>
    <div class="nav-right">
        <div class="links" id="links">
            <button class="drawer-close" aria-label="Chiudi menu" onclick="document.body.classList.remove('mo')">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            
            <a href="<?php echo e(url("/" . app()->getLocale() . "")); ?>" class="<?php echo e(request()->is(app()->getLocale()) ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.home'); ?></a>
            <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi'))); ?>" class="<?php echo e(request()->is("*/eventi") || request()->is("*/events") ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.events'); ?></a>
            <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'community' : 'comunita'))); ?>" class="<?php echo e(request()->is("*/comunita") || request()->is("*/community") ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.community'); ?></a>
            <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'news' : 'notizie'))); ?>" class="<?php echo e(request()->is("*/notizie") || request()->is("*/news") ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.news'); ?></a>
            <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'territory' : 'territorio'))); ?>" class="<?php echo e(request()->is("*/territorio") || request()->is("*/territory") ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.territory'); ?></a>
            <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'tastes' : 'sapori'))); ?>" class="<?php echo e(request()->is("*/sapori") || request()->is("*/tastes") ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.tastes'); ?></a>
            <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'discover' : 'scopri'))); ?>" class="<?php echo e(request()->is("*/scopri") || request()->is("*/discover") ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.discover'); ?></a>
            <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'gallery' : 'galleria'))); ?>" class="<?php echo e(request()->is("*/galleria") || request()->is("*/gallery") ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.gallery'); ?></a>
            <a href="<?php echo e(url("/" . app()->getLocale() . "/pro-loco")); ?>" class="<?php echo e(request()->is("*/pro-loco") ? 'active' : ''); ?>"><?php echo app('translator')->get('navigation.pro_loco'); ?></a>
            
            <div class="lang-switcher">
                <a href="<?php echo e(localized_route(request()->path(), 'it')); ?>" class="lang-link <?php echo e(!(app()->getLocale() === 'en') ? 'active' : ''); ?>">IT</a>
                <span class="lang-sep">|</span>
                <a href="<?php echo e(localized_route(request()->path(), 'en')); ?>" class="lang-link <?php echo e((app()->getLocale() === 'en') ? 'active' : ''); ?>">EN</a>
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
<?php /**PATH C:\Users\vince\OneDrive\Desktop\proloco-pietrapertosa\resources\views/components/header.blade.php ENDPATH**/ ?>