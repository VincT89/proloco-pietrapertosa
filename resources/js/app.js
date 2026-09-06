import './bootstrap';
import './chatbot.js';

const initApp = () => {
    const english = document.documentElement.lang === 'en';
    const text = (it, en) => english ? en : it;
    const nav = document.getElementById('nav');
    const menu = document.getElementById('links');
    const toggle = document.getElementById('hmb');
    const compactMenu = window.matchMedia('(max-width: 1279px)');
    const background = [...document.querySelectorAll('#main-content, footer, #proloco-chatbot, #cookieBanner')];
    const setMenu = (open, restoreFocus = true) => {
        open = compactMenu.matches && open;
        document.body.classList.toggle('mo', open);
        toggle?.setAttribute('aria-expanded', String(open));
        toggle?.setAttribute('aria-label', open ? text('Chiudi menu', 'Close menu') : text('Apri menu', 'Open menu'));
        if (menu) menu.inert = compactMenu.matches && !open;
        background.forEach(element => { element.inert = open; });
        if (open) menu?.querySelector('a')?.focus();
        else if (restoreFocus && compactMenu.matches) toggle?.focus();
    };
    toggle?.addEventListener('click', () => setMenu(!document.body.classList.contains('mo')));
    document.querySelector('.nav-overlay')?.addEventListener('click', () => setMenu(false));
    menu?.addEventListener('click', event => {
        if (event.target.closest('a') && compactMenu.matches) setMenu(false, false);
    });
    compactMenu.addEventListener('change', () => setMenu(false, false));
    setMenu(false, false);
    const updateNav = () => nav?.classList.toggle('sc', window.scrollY > 40);
    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav();

    document.addEventListener('keydown', event => {
        if (!document.body.classList.contains('mo')) return;
        if (event.key === 'Escape') { event.preventDefault(); setMenu(false); }
        if (event.key === 'Tab') {
            const focusable = [...menu.querySelectorAll('a, button'), toggle].filter(el => el.getClientRects().length);
            const first = focusable[0], last = focusable.at(-1);
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        }
    });

    // External content is loaded only after an explicit, persisted choice.
    const consent = () => document.cookie.split(';').map(part => part.trim()).find(part => part.startsWith('proloco_cookie_consent='))?.split('=')[1];
    window.hasExternalConsent = () => consent() === 'external_accepted';
    window.hasRejectedConsent = () => consent() === 'external_rejected';
    const banner = document.getElementById('cookieBanner');
    let preferenceTrigger = null;
    const embedPlaceholders = new Map();
    document.querySelectorAll('[data-external-embed="true"]').forEach(el => embedPlaceholders.set(el, el.innerHTML));
    window.loadExternalEmbeds = () => {
        embedPlaceholders.forEach((placeholder, el) => {
            if (!window.hasExternalConsent()) {
                if (el.dataset.loaded === 'true') el.innerHTML = placeholder;
                delete el.dataset.loaded;
                return;
            }
            if (el.dataset.loaded === 'true') return;
            const iframe = document.createElement('iframe');
            iframe.src = el.dataset.src;
            iframe.title = el.dataset.title || text('Contenuto esterno', 'External content');
            iframe.loading = 'lazy';
            iframe.allowFullscreen = true;
            iframe.allow = 'fullscreen; picture-in-picture';
            el.replaceChildren(iframe);
            el.dataset.loaded = 'true';
        });
    };
    window.setCookieConsent = value => {
        if (!['external_accepted', 'external_rejected'].includes(value)) return;
        document.cookie = 'proloco_cookie_consent=' + value + ';max-age=15552000;path=/;SameSite=Lax' + (location.protocol === 'https:' ? ';Secure' : '');
        banner?.classList.add('is-hidden');
        preferenceTrigger?.focus();
        preferenceTrigger = null;
        window.loadExternalEmbeds();
        if (dialog?.open && window.lbData.images[window.lbData.index]?.type === 'video') window.updateLightbox();
    };
    window.manageCookiePreferences = () => {
        preferenceTrigger = document.activeElement;
        banner?.classList.remove('is-hidden');
        document.getElementById('cookieRejectBtn')?.focus();
    };
    if (!window.hasExternalConsent() && !window.hasRejectedConsent()) banner?.classList.remove('is-hidden');
    window.loadExternalEmbeds();
    document.getElementById('cookieAcceptBtn')?.addEventListener('click', () => window.setCookieConsent('external_accepted'));
    document.getElementById('cookieRejectBtn')?.addEventListener('click', () => window.setCookieConsent('external_rejected'));
    document.addEventListener('click', event => {
        if (event.target.closest('.external-embed-button')) window.setCookieConsent('external_accepted');
    });

    const dialog = document.getElementById('gallery-modal');
    const container = document.getElementById('lbMediaContainer');
    const loader = document.getElementById('lbLoader');
    let galleryTrigger = null;
    let renderVersion = 0;
    window.lbData = { images: [], index: 0 };
    window.openGallery = (images, index = 0) => {
        if (!dialog || !Array.isArray(images) || !images.length) return;
        window.lbData.images = images.map(item => typeof item === 'string' ? { type: 'image', url: item, alt: '' } : item);
        window.lbData.index = Math.max(0, Math.min(Number(index) || 0, images.length - 1));
        galleryTrigger = document.activeElement;
        dialog.showModal();
        document.body.classList.add('gallery-open');
        window.updateLightbox();
    };
    window.closeGallery = event => { event?.stopPropagation(); dialog?.close(); };
    dialog?.addEventListener('close', () => {
        renderVersion++;
        container?.replaceChildren();
        document.body.classList.remove('gallery-open');
        galleryTrigger?.focus({ preventScroll: true });
    });
    dialog?.addEventListener('click', event => { if (event.target === dialog) window.closeGallery(); });
    window.lbPrev = event => {
        event?.stopPropagation();
        window.lbData.index = (window.lbData.index - 1 + window.lbData.images.length) % window.lbData.images.length;
        window.updateLightbox();
    };
    window.lbNext = event => {
        event?.stopPropagation();
        window.lbData.index = (window.lbData.index + 1) % window.lbData.images.length;
        window.updateLightbox();
    };
    dialog?.addEventListener('keydown', event => {
        if (event.target.matches('video, input, textarea')) return;
        if (event.key === 'ArrowLeft') { event.preventDefault(); window.lbPrev(); }
        if (event.key === 'ArrowRight') { event.preventDefault(); window.lbNext(); }
    });
    window.updateLightbox = () => {
        const item = window.lbData.images[window.lbData.index];
        if (!item || !container) return;
        const version = ++renderVersion;
        container.replaceChildren();
        loader.hidden = false;
        document.getElementById('lbCapText').textContent = item.alt || '';
        document.getElementById('lbCapCount').textContent = window.lbData.images.length > 1 ? (window.lbData.index + 1) + ' / ' + window.lbData.images.length : '';
        ['lb-prev', 'lb-next'].forEach(id => { document.getElementById(id).hidden = window.lbData.images.length < 2; });
        const done = () => { if (version === renderVersion) loader.hidden = true; };
        const failed = () => {
            if (version !== renderVersion) return;
            done();
            const message = document.createElement('p');
            message.className = 'media-error';
            message.textContent = text('Non è stato possibile caricare questo contenuto. Riprova o passa al successivo.', 'This content could not be loaded. Try again or go to the next item.');
            const retry = document.createElement('button');
            retry.type = 'button';
            retry.textContent = text('Riprova', 'Retry');
            retry.addEventListener('click', window.updateLightbox);
            container.replaceChildren(message, retry);
        };
        if (item.type === 'video' && item.embed_url) {
            done();
            if (!window.hasExternalConsent()) {
                const message = document.createElement('p');
                message.textContent = text('Per vedere questo video occorre abilitare i contenuti esterni.', 'Enable external content to watch this video.');
                const accept = document.createElement('button');
                accept.type = 'button';
                accept.textContent = text('Abilita contenuti esterni', 'Enable external content');
                accept.addEventListener('click', () => window.setCookieConsent('external_accepted'));
                container.append(message, accept);
                return;
            }
            const iframe = document.createElement('iframe');
            iframe.src = item.embed_url;
            iframe.title = item.alt || text('Video della galleria', 'Gallery video');
            iframe.allow = 'fullscreen; picture-in-picture';
            iframe.allowFullscreen = true;
            container.append(iframe);
        } else if (item.type === 'video') {
            const video = document.createElement('video');
            video.controls = true;
            video.playsInline = true;
            video.preload = 'metadata';
            video.addEventListener('loadedmetadata', done);
            video.addEventListener('error', failed);
            video.src = item.url;
            container.append(video);
        } else {
            const img = new Image();
            img.alt = item.alt || '';
            img.addEventListener('load', done);
            img.addEventListener('error', failed);
            img.src = item.url;
            container.append(img);
        }
    };

    document.addEventListener('click', event => {
        const indexLink = event.target.closest('.place-index-mobile a');
        if (indexLink) indexLink.closest('details').open = false;
        const trigger = event.target.closest('button[data-gallery], button[data-gallery-index]');
        if (!trigger) return;
        const source = trigger.closest('[data-gallery]');
        try { window.openGallery(JSON.parse(source.dataset.gallery), trigger.dataset.galleryIndex || trigger.dataset.index || 0); }
        catch { /* An invalid gallery must not break navigation. */ }
    });
    // Retain keyboard access for existing content that still uses inline gallery handlers.
    document.querySelectorAll('[onclick*="openGallery"]').forEach(el => {
        if (el.matches('button, a')) return;
        el.tabIndex = 0;
        el.setAttribute('role', 'button');
        el.setAttribute('aria-label', text('Ingrandisci immagine', 'Enlarge image') + (el.querySelector('img')?.alt ? ': ' + el.querySelector('img').alt : ''));
        el.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); el.click(); }
        });
    });
    // Existing image carousels remain manually selectable, without moving text or autoplay.
    document.querySelectorAll('.auto-carousel').forEach(carousel => {
        const images = [...carousel.querySelectorAll('img')];
        carousel.querySelectorAll('.carousel-dot').forEach((dot, index) => {
            dot.addEventListener('click', event => {
                event.stopPropagation();
                images.forEach((image, i) => {
                    image.style.opacity = i === index ? '1' : '0';
                    image.style.zIndex = i === index ? '1' : '0';
                });
            });
        });
    });
};
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initApp);
else initApp();
