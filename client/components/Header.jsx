"use client";

import React, { useState, useEffect } from "react";
import Link from "next/link";
import { usePathname, useSearchParams } from "next/navigation";
import { Globe, X } from "lucide-react";
import { useStore } from "./Store";
import { getLang, withLang } from "../utils/lang";

export default function Header() {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const pathname = usePathname() || '';
  const searchParams = useSearchParams();
  
  const currentLang = getLang(searchParams);
  const isEn = currentLang === "en";

  useEffect(() => {
    return () => {
      document.body.classList.remove("mo");
    };
  }, [pathname, searchParams]);

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

  const getRoute = (path) => {
    return withLang(path, currentLang);
  };

  return (
    <nav id="nav">
      <Link href={getRoute("/")} className="brand" onClick={closeMenu}>
        <img src="/images/logo.png" alt="Logo Associazione Pro Loco Pietrapertosana" />
        <span className="t">
          <b>Pietrapertosa</b>
          <small>Pro Loco · Dolomiti Lucane</small>
        </span>
      </Link>
      <div className="nav-right">
        <div className="links" id="links">
          <button className="drawer-close" onClick={closeMenu} aria-label="Chiudi menu">
            <X size={24} />
          </button>
          
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
          <div className="lang-switcher">
            <Link href={withLang(pathname, "it")} className={`lang-link ${!isEn ? 'active' : ''}`} onClick={closeMenu}>IT</Link>
            <span className="lang-sep">|</span>
            <Link href={withLang(pathname, "en")} className={`lang-link ${isEn ? 'active' : ''}`} onClick={closeMenu}>EN</Link>
          </div>
        </div>

        <button className="hmb" id="hmb" aria-label="Menu" onClick={toggleMenu}>
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </nav>
  );
}