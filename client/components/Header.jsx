"use client";

import React, { useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { Lock, Globe } from "lucide-react";
import { useStore } from "./Store";

export default function Header() {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const { isAdmin, openLoginModal } = useStore();
  const pathname = usePathname() || '';
  
  const isEn = pathname.startsWith('/en');

  const toggleMenu = () => {
    setIsMobileMenuOpen(!isMobileMenuOpen);
    if (!isMobileMenuOpen) {
      document.body.classList.add("mo");
    } else {
      document.body.classList.remove("mo");
    }
  };

  const closeMenu = () => {
    setIsMobileMenuOpen(false);
    document.body.classList.remove("mo");
  };

  const itToEn = {
    '/eventi': '/events',
    '/comunita': '/community',
    '/notizie': '/news',
    '/territorio': '/territory',
    '/sapori': '/tastes',
    '/scopri': '/discover',
    '/storie': '/stories',
    '/galleria': '/gallery',
    '/pro-loco': '/pro-loco',
    '/': ''
  };

  const enToIt = Object.fromEntries(Object.entries(itToEn).map(([it, en]) => [en, it]));

  const getRoute = (path) => {
    if (isEn) {
      return `/en${itToEn[path] !== undefined ? itToEn[path] : path}`;
    }
    return path;
  };

  // Remove /en from pathname
  let rawPath = pathname.startsWith('/en') ? (pathname.replace('/en', '') || '/') : pathname;
  
  // If we are on EN, rawPath might be '/news', we need to map it back to '/notizie' for IT
  // If we are on IT, rawPath is already '/notizie'
  const itPath = isEn ? (enToIt[rawPath] || rawPath) : rawPath;
  const enPath = `/en${itToEn[itPath] !== undefined ? itToEn[itPath] : itPath}`;

  const itUrl = itPath;
  const enUrl = enPath;

  return (
    <nav id="nav">
      <Link href={getRoute("/")} className="brand" onClick={closeMenu}>
        <img src="/images/logo.png" alt="Logo Associazione Pro Loco Pietrapertosana" />
        <span className="t">
          <b>Pietrapertosa</b>
          <small>Pro Loco · Dolomiti Lucane</small>
        </span>
      </Link>
      <button className="hmb" id="hmb" aria-label="Menu" onClick={toggleMenu}>
        <span></span>
        <span></span>
        <span></span>
      </button>
      <div className="links" id="links">
        <Link href={getRoute("/")} className={pathname === getRoute("/") ? "active" : ""} onClick={closeMenu}>Home</Link>
        <Link href={getRoute("/eventi")} className={pathname.includes("/eventi") || pathname.includes("/events") ? "active" : ""} onClick={closeMenu}>{isEn ? "Events" : "Eventi"}</Link>
        <Link href={getRoute("/comunita")} className={pathname.includes("/comunita") || pathname.includes("/community") ? "active" : ""} onClick={closeMenu}>{isEn ? "Community" : "Comunità"}</Link>
        <Link href={getRoute("/notizie")} className={pathname.includes("/notizie") || pathname.includes("/news") ? "active" : ""} onClick={closeMenu}>{isEn ? "News" : "Notizie"}</Link>
        <Link href={getRoute("/territorio")} className={pathname.includes("/territorio") || pathname.includes("/territory") ? "active" : ""} onClick={closeMenu}>{isEn ? "Territory" : "Territorio"}</Link>
        <Link href={getRoute("/sapori")} className={pathname.includes("/sapori") || pathname.includes("/tastes") ? "active" : ""} onClick={closeMenu}>{isEn ? "Tastes" : "Sapori"}</Link>
        <Link href={getRoute("/scopri")} className={pathname.includes("/scopri") || pathname.includes("/discover") ? "active" : ""} onClick={closeMenu}>{isEn ? "Discover & Live" : "Scopri & Vivi"}</Link>
        <Link href={getRoute("/galleria")} className={pathname.includes("/galleria") || pathname.includes("/gallery") ? "active" : ""} onClick={closeMenu}>{isEn ? "Gallery" : "Galleria"}</Link>
        <Link href={getRoute("/pro-loco")} className={pathname.includes("/pro-loco") ? "active" : ""} onClick={closeMenu}>Pro Loco</Link>

        {/* Language Switcher */}
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginLeft: '10px' }}>
          <Globe size={14} style={{ color: 'var(--stone)' }} />
          <Link href={itUrl} style={{ color: isEn ? 'var(--stone)' : 'var(--gold-soft)', fontWeight: isEn ? 'normal' : 'bold', letterSpacing: '1px', fontSize: '0.65rem' }}>IT</Link>
          <span style={{ color: 'var(--stone)' }}>|</span>
          <Link href={enUrl} style={{ color: !isEn ? 'var(--stone)' : 'var(--gold-soft)', fontWeight: !isEn ? 'normal' : 'bold', letterSpacing: '1px', fontSize: '0.65rem' }}>EN</Link>
        </div>
        
        {isAdmin ? (
          <Link href="/admin/dashboard" className="adm-link" onClick={closeMenu}>
            <Lock className="ico" />
            <span>{isEn ? "Dashboard" : "Siete dentro!"}</span>
          </Link>
        ) : (
          <Link href="/admin/login" className="adm-link" title="Accesso riservato alla Pro Loco" onClick={closeMenu}>
            <Lock className="ico" />
            <span>{isEn ? "Restricted Access" : "Accesso riservato"}</span>
          </Link>
        )}
      </div>
    </nav>
  );
}