import './bootstrap';

const initApp = () => {
    // Reveal letters logic (.wr animations)
    document.querySelectorAll(".wr").forEach((el) => {
        if (el.classList.contains("split-done")) return;
        el.classList.add("split-done");

        const split = (node) => {
            [...node.childNodes].forEach((ch) => {
                if (ch.nodeType === 3) {
                    const text = ch.textContent || "";
                    const frag = document.createDocumentFragment();
                    text.split(/(\s+)/).forEach((tok) => {
                        if (/^\s+$/.test(tok) || tok === "") {
                            frag.appendChild(document.createTextNode(tok));
                            return;
                        }
                        const w = document.createElement("span");
                        w.className = "w";
                        const i = document.createElement("i");
                        i.textContent = tok;
                        w.appendChild(i);
                        frag.appendChild(w);
                    });
                    node.replaceChild(frag, ch);
                } else if (ch.nodeType === 1 && ch.tagName !== "I") {
                    split(ch);
                }
            });
        };
        split(el);
        el.querySelectorAll(".w i").forEach((i, n) => {
            i.style.transitionDelay = n * 0.035 + "s";
        });
    });

    // Intersection Observer per animazioni in ingresso (.in)
    const io = new IntersectionObserver(
        (es) => es.forEach((e) => {
            if (e.isIntersecting) {
                e.target.classList.add("in");
                io.unobserve(e.target);
            }
        }),
        { threshold: 0.14 }
    );

    const obs = () => {
        document.querySelectorAll(".wr, .cur, .fad").forEach((el) => {
            if (!el.classList.contains("in")) io.observe(el);
        });
    };
    obs();
    window.obsAll = obs;

    // Observer per contatori numerici (stats)
    const cio = new IntersectionObserver(
        (es) => es.forEach((e) => {
            if (!e.isIntersecting) return;
            const el = e.target;
            const t = +(el.dataset.cnt || 0);
            const t0 = performance.now();
            (function tick(now) {
                const pr = Math.min((now - t0) / 1900, 1);
                el.textContent = Math.round(t * (1 - Math.pow(1 - pr, 3))).toLocaleString("it-IT");
                if (pr < 1) requestAnimationFrame(tick);
            })(t0);
            cio.unobserve(el);
        }),
        { threshold: 0.6 }
    );
    document.querySelectorAll("[data-cnt]").forEach((el) => cio.observe(el));

    // Nav and Banner scroll (parallax & header shrink)
    const nav = document.getElementById("nav");
    const bannerBg = document.getElementById("bannerBg");
    
    const onScroll = () => {
        if(nav) nav.classList.toggle("sc", window.scrollY > 60);
        
        const fb = document.getElementById("fBig");
        if (fb) {
            const r = fb.getBoundingClientRect();
            if (r.top < window.innerHeight) {
                fb.style.letterSpacing = Math.max(0, r.top - window.innerHeight * 0.4) * 0.02 + "px";
            }
        }
        if (bannerBg && bannerBg.parentElement) {
            const br = bannerBg.parentElement.getBoundingClientRect();
            if (br.bottom > 0 && br.top < window.innerHeight) {
                bannerBg.style.transform = `translateY(${(br.top - window.innerHeight / 2) * 0.12}px)`;
            }
        }
        
        const heroMedia = document.getElementById("heroMedia");
        if (heroMedia) {
            heroMedia.style.transform = `translateY(${window.scrollY * 0.45}px)`;
        }
    };
    
    window.addEventListener("scroll", onScroll, { passive: true });

    // AutoCarousel logic
    document.querySelectorAll('.auto-carousel').forEach(carousel => {
        const interval = parseInt(carousel.dataset.interval || 4000);
        const images = carousel.querySelectorAll('img');
        const dots = carousel.querySelectorAll('.carousel-dot');
        if (images.length <= 1) return;
        
        let currentIndex = 0;

        const updateCarousel = (newIndex) => {
            images[currentIndex].style.opacity = '0';
            images[currentIndex].style.zIndex = '0';
            if(dots.length > 0) dots[currentIndex].style.background = 'rgba(255,255,255,0.4)';
            
            currentIndex = newIndex;
            
            images[currentIndex].style.opacity = '1';
            images[currentIndex].style.zIndex = '1';
            if(dots.length > 0) dots[currentIndex].style.background = 'var(--gold)';
        };

        let timer = setInterval(() => {
            updateCarousel((currentIndex + 1) % images.length);
        }, interval);

        dots.forEach(dot => {
            dot.addEventListener('click', (e) => {
                e.stopPropagation();
                clearInterval(timer);
                updateCarousel(parseInt(dot.dataset.index));
                timer = setInterval(() => {
                    updateCarousel((currentIndex + 1) % images.length);
                }, interval);
            });
        });
    });

    window.lbData = { images: [], index: 0 };

    window.openGallery = function(images, index = 0) {
        window.lbData.images = images;
        window.lbData.index = index;
        window.updateLightbox();
        document.getElementById('gallery-modal').style.display = 'flex';
    };

    window.closeGallery = function() {
        document.getElementById('gallery-modal').style.display = 'none';
        window.lbData.images = [];
    };

    window.lbPrev = function(e) {
        e.stopPropagation();
        if (window.lbData.images.length > 0) {
            window.lbData.index = (window.lbData.index - 1 + window.lbData.images.length) % window.lbData.images.length;
            window.updateLightbox();
        }
    };

    window.lbNext = function(e) {
        e.stopPropagation();
        if (window.lbData.images.length > 0) {
            window.lbData.index = (window.lbData.index + 1) % window.lbData.images.length;
            window.updateLightbox();
        }
    };

    window.updateLightbox = function() {
        const { images, index } = window.lbData;
        if (images.length === 0) return;

        const media = images[index];
        const container = document.getElementById('lbMediaContainer');
        const loader = document.getElementById('lbLoader');
        const modal = document.getElementById('gallery-modal');

        if (!container) return;

        container.innerHTML = '';
        if (loader) loader.classList.add('is-visible');
        if (modal) modal.classList.add('is-loading');

        const showElement = (el) => {
            el.className = "lb-img";
            container.innerHTML = '';
            container.appendChild(el);
            if (loader) loader.classList.remove('is-visible');
            if (modal) modal.classList.remove('is-loading');
        };

        if (typeof media === 'string' || media.type === 'image') {
            const url = typeof media === 'string' ? media : media.url;
            const preload = new Image();

            preload.onload = () => {
                const img = document.createElement('img');
                img.src = url;
                img.alt = media.alt || '';
                showElement(img);
            };

            preload.onerror = () => {
                if (loader) loader.classList.remove('is-visible');
                if (modal) modal.classList.remove('is-loading');
            };

            preload.src = url;
        } else if (media.type === 'video') {
            let el;

            if (media.provider === 'cloudinary') {
                el = document.createElement('video');
                el.controls = true;
                el.src = media.url;
                el.preload = 'metadata';
            } else {
                el = document.createElement('iframe');
                el.src = media.embed_url;
                el.setAttribute("allowfullscreen", "true");
                el.setAttribute("frameborder", "0");
            }

            showElement(el);
        }

        const prevBtn = document.getElementById('lb-prev');
        const nextBtn = document.getElementById('lb-next');
        const cap = document.getElementById('lbCap');

        if (images.length > 1) {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
            cap.style.display = 'block';
            document.getElementById('lbCapCount').textContent = `(${index + 1} / ${images.length})`;
        } else {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            cap.style.display = 'none';
        }
    };

    window.handleCollageSwap = function(el) {
        const parent = el.closest('.interactive-collage');
        const clickedRank = parseInt(el.dataset.rank);
        if (clickedRank === 0) return;

        const items = parent.querySelectorAll('.collage-item');
        items.forEach(item => {
            const rank = parseInt(item.dataset.rank);
            if (rank === clickedRank) {
                item.dataset.rank = 0;
            } else if (rank === 0) {
                item.dataset.rank = clickedRank;
            }
        });
        window.updateCollageStyles(parent);
    };

    window.updateCollageStyles = function(parent) {
        parent.querySelectorAll('.collage-item').forEach(item => {
            const rank = parseInt(item.dataset.rank);
            if (rank === 0) {
                Object.assign(item.style, { width: '80%', height: '85%', top: '0', left: '0', bottom: 'auto', right: 'auto', zIndex: 1, border: 'none', boxShadow: 'none', cursor: 'default' });
                const overlay = item.querySelector('.collage-overlay');
                if(overlay) overlay.style.display = 'none';
            } else if (rank === 1) {
                Object.assign(item.style, { width: '50%', height: '50%', bottom: '0', right: '0', top: 'auto', left: 'auto', zIndex: 2, border: '8px solid var(--ink)', boxShadow: 'none', cursor: 'pointer' });
                const overlay = item.querySelector('.collage-overlay');
                if(overlay) overlay.style.display = 'block';
            } else if (rank === 2) {
                Object.assign(item.style, { width: '35%', height: '35%', top: '15%', right: '-2%', bottom: 'auto', left: 'auto', zIndex: 3, border: '8px solid var(--ink)', boxShadow: '0 20px 40px rgba(0,0,0,0.5)', cursor: 'pointer' });
                const overlay = item.querySelector('.collage-overlay');
                if(overlay) overlay.style.display = 'block';
            }
        });
    };

    document.querySelectorAll('.interactive-collage').forEach(window.updateCollageStyles);

    // ============ COOKIE & EXTERNAL EMBEDS ============
    window.hasExternalConsent = function() {
        return document.cookie.split('; ').find(row => row.startsWith('proloco_cookie_consent=external_accepted'));
    };

    window.hasRejectedConsent = function() {
        return document.cookie.split('; ').find(row => row.startsWith('proloco_cookie_consent=external_rejected'));
    };

    window.setCookieConsent = function(value) {
        const d = new Date();
        d.setTime(d.getTime() + (180*24*60*60*1000)); // 6 months
        document.cookie = "proloco_cookie_consent=" + value + ";expires=" + d.toUTCString() + ";path=/;SameSite=Lax;Secure";
        const banner = document.getElementById('cookieBanner');
        if (banner) banner.classList.add('is-hidden');
        if (value === 'external_accepted') {
            window.loadExternalEmbeds();
        }
    };

    window.loadExternalEmbeds = function() {
        if (!window.hasExternalConsent()) return;
        document.querySelectorAll('[data-external-embed="true"]').forEach(container => {
            if (container.dataset.loaded) return;
            const src = container.dataset.src;
            const iframe = document.createElement('iframe');
            iframe.src = src;
            iframe.setAttribute('allowfullscreen', 'true');
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('loading', 'lazy');
            // If instagram, add allowtransparency
            if (container.dataset.provider === 'instagram') {
                iframe.setAttribute('allowtransparency', 'true');
            }
            container.innerHTML = '';
            container.appendChild(iframe);
            container.dataset.loaded = 'true';
        });
    };

    window.manageCookiePreferences = function() {
        const banner = document.getElementById('cookieBanner');
        if (banner) banner.classList.remove('is-hidden');
    };

    const cookieBanner = document.getElementById('cookieBanner');
    if (cookieBanner && !window.hasExternalConsent() && !window.hasRejectedConsent()) {
        setTimeout(() => cookieBanner.classList.remove('is-hidden'), 1000);
    } else if (window.hasExternalConsent()) {
        window.loadExternalEmbeds();
    }

    const acceptBtn = document.getElementById('cookieAcceptBtn');
    if (acceptBtn) acceptBtn.addEventListener('click', () => window.setCookieConsent('external_accepted'));
    
    const rejectBtn = document.getElementById('cookieRejectBtn');
    if (rejectBtn) rejectBtn.addEventListener('click', () => window.setCookieConsent('external_rejected'));

    // Delegate click on placeholder buttons to accept consent
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('external-embed-button')) {
            window.setCookieConsent('external_accepted');
        }
    });

    // Update Lightbox logic to check for consent
    const originalUpdateLightbox = window.updateLightbox;
    window.updateLightbox = function() {
        const { images, index } = window.lbData;
        if (images.length === 0) return;
        
        const media = images[index];
        if (media.type === 'video' && media.provider !== 'cloudinary') {
            if (!window.hasExternalConsent()) {
                const container = document.getElementById('lbMediaContainer');
                if (container) {
                    container.innerHTML = `
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:white;text-align:center;">
                            <p style="margin-bottom: 20px;">Questo contenuto esterno è bloccato.</p>
                            <button class="ed-btn ed-btn-small external-embed-button">Abilita contenuti esterni</button>
                        </div>
                    `;
                }
                const cap = document.getElementById('lbCap');
                if (cap) cap.style.display = 'block';
                return;
            }
        }
        originalUpdateLightbox();
    };
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}
