"use client";

import { useEffect } from "react";

export default function ClientScripts() {
  useEffect(() => {
    // Reveal letters logic
    document.querySelectorAll(".wr").forEach((el) => {
      // Evitiamo di farlo più volte se React ricarica
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

    // Intersection Observer per animazioni in ingresso
    const io = new IntersectionObserver(
      (es) =>
        es.forEach((e) => {
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
    window.obsAll = obs; // Esponiamo per chiamarlo quando si caricano nuove news

    // Observer per contatori numerici
    const cio = new IntersectionObserver(
      (es) =>
        es.forEach((e) => {
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

    // Nav and Banner scroll
    const nav = document.getElementById("nav");
    const bannerBg = document.getElementById("bannerBg");
    
    const onScroll = () => {
      nav?.classList.toggle("sc", window.scrollY > 60);
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

    return () => {
      io.disconnect();
      cio.disconnect();
      window.removeEventListener("scroll", onScroll);
    };
  }, []);

  return null;
}
