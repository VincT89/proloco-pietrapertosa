"use client";

import React, { useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { Lock } from "lucide-react";
import { useStore } from "./Store";

export default function Header() {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const { isAdmin, openLoginModal } = useStore();
  const pathname = usePathname();

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

  return (
    <nav id="nav">
      <a href="#top" className="brand" onClick={closeMenu}>
        <img src="/images/logo.png" alt="Logo Associazione Pro Loco Pietrapertosana" />
        <span className="t">
          <b>Pietrapertosa</b>
          <small>Pro Loco · Dolomiti Lucane</small>
        </span>
      </a>
      <button className="hmb" id="hmb" aria-label="Menu" onClick={toggleMenu}>
        <span></span>
        <span></span>
        <span></span>
      </button>
      <div className="links" id="links">
        <Link href="/" className={pathname === "/" ? "active" : ""} onClick={closeMenu}>Home</Link>
        <Link href="/eventi" className={pathname === "/eventi" ? "active" : ""} onClick={closeMenu}>Eventi</Link>
        <Link href="/comunita" className={pathname === "/comunita" ? "active" : ""} onClick={closeMenu}>Comunità</Link>
        <Link href="/notizie" className={pathname === "/notizie" ? "active" : ""} onClick={closeMenu}>Notizie</Link>
        <Link href="/territorio" className={pathname === "/territorio" ? "active" : ""} onClick={closeMenu}>Territorio</Link>
        <Link href="/sapori" className={pathname === "/sapori" ? "active" : ""} onClick={closeMenu}>Sapori</Link>
        <Link href="/scopri" className={pathname === "/scopri" ? "active" : ""} onClick={closeMenu}>Scopri & Vivi</Link>
        <Link href="/pro-loco" className={pathname === "/pro-loco" ? "active" : ""} onClick={closeMenu}>Pro Loco</Link>
        
        {isAdmin ? (
          <button className="adm-link" onClick={() => { closeMenu(); document.getElementById('admPanel')?.scrollIntoView({ behavior: 'smooth', block: 'center' }) }}>
            <Lock className="ico" />
            <span>Siete dentro!</span>
          </button>
        ) : (
          <button className="adm-link" title="Accesso riservato alla Pro Loco" onClick={() => { closeMenu(); openLoginModal(); }}>
            <Lock className="ico" />
            <span>Accesso riservato</span>
          </button>
        )}
      </div>
    </nav>
  );
}