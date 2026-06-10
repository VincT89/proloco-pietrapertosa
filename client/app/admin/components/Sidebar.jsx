"use client";
import { safeJsonParse } from '@/utils/safeJson';

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { LayoutDashboard, Image as ImageIcon, FileText, Calendar, LogOut } from 'lucide-react';

export default function Sidebar() {
  const pathname = usePathname();
  const router = useRouter();
  const [user, setUser] = useState(null);

  useEffect(() => {
    const userData = localStorage.getItem('admin_user');
    if (userData) setUser(safeJsonParse(userData));
  }, []);

  const handleLogout = () => {
    localStorage.removeItem('admin_token');
    localStorage.removeItem('admin_user');
    router.push('/admin/login');
  };

  const navItems = [
    { name: 'Dashboard', path: '/admin/dashboard', icon: <LayoutDashboard size={20} /> },
    { name: 'Media Library', path: '/admin/media', icon: <ImageIcon size={20} /> },
    { name: 'Notizie', path: '/admin/news', icon: <FileText size={20} /> },
    { name: 'Eventi', path: '/admin/events', icon: <Calendar size={20} /> },
    { name: 'Testi Pagine', path: '/admin/pages', icon: <FileText size={20} /> },
    { name: 'Attività e Territorio', path: '/admin/directory', icon: <LayoutDashboard size={20} /> },
  ];

  return (
    <div className="admin-sidebar">
      <div className="admin-sidebar-header">
        <Link href="/admin/dashboard" className="admin-sidebar-brand">
          Pro Loco Admin
        </Link>
      </div>

      <div className="admin-sidebar-nav">
        
        <div className="admin-sidebar-section-title first">Sistema</div>
        <Link href="/admin/dashboard" className={`admin-nav-item ${pathname === '/admin/dashboard' ? 'active' : ''}`}>
          <LayoutDashboard size={18} /> Dashboard
        </Link>
        <Link href="/admin/media" className={`admin-nav-item ${pathname === '/admin/media' ? 'active' : ''}`}>
          <ImageIcon size={18} /> Libreria Media
        </Link>

        <div className="admin-sidebar-section-title">Comunicazione</div>
        <Link href="/admin/events" className={`admin-nav-item ${pathname === '/admin/events' ? 'active' : ''}`}>
          <Calendar size={18} /> Calendario Eventi
        </Link>
        <Link href="/admin/news" className={`admin-nav-item ${pathname === '/admin/news' ? 'active' : ''}`}>
          <FileText size={18} /> Notizie e Avvisi
        </Link>

        <div className="admin-sidebar-section-title">Contenuti Pagine</div>
        
        <Link href="/admin/pages" className={`admin-nav-item ${pathname === '/admin/pages' ? 'active' : ''}`}>
          <FileText size={18} /> Testi Pagine
        </Link>
        <Link href="/admin/directory?category=eventi_annuali" className={`admin-nav-item ${pathname.includes('/directory') && pathname.includes('eventi_annuali') ? 'active' : ''}`}>
          <span className="sidebar-bullet"></span> Eventi Annuali
        </Link>
        <Link href="/admin/directory?category=comunita" className={`admin-nav-item ${pathname.includes('/directory') && pathname.includes('comunita') ? 'active' : ''}`}>
          <span className="sidebar-bullet"></span> Le Associazioni
        </Link>
        <Link href="/admin/directory?category=territorio_aziende" className={`admin-nav-item ${pathname.includes('/directory') && pathname.includes('territorio_aziende') ? 'active' : ''}`}>
          <span className="sidebar-bullet"></span> Aziende Agricole
        </Link>
        <Link href="/admin/directory?category=territorio_foodtruck" className={`admin-nav-item ${pathname.includes('/directory') && pathname.includes('territorio_foodtruck') ? 'active' : ''}`}>
          <span className="sidebar-bullet"></span> Food Truck
        </Link>
        <Link href="/admin/directory?category=territorio_artigiani" className={`admin-nav-item ${pathname.includes('/directory') && pathname.includes('territorio_artigiani') ? 'active' : ''}`}>
          <span className="sidebar-bullet"></span> Gli Artigiani
        </Link>
        <Link href="/admin/directory?category=sapori_piatti" className={`admin-nav-item ${pathname.includes('/directory') && pathname.includes('sapori_piatti') ? 'active' : ''}`}>
          <span className="sidebar-bullet"></span> Piatti Tipici
        </Link>
        <Link href="/admin/gallery" className={`admin-nav-item ${pathname.includes('/admin/gallery') ? 'active' : ''}`}>
          <ImageIcon size={18} /> Gestione Galleria
        </Link>
      </div>

      <div className="admin-sidebar-footer">
        {user && <div className="admin-sidebar-user">{user.name}</div>}
        
        <Link href="/admin/settings" className="admin-logout-btn no-underline">
          Impostazioni
        </Link>

        <Link href="/" className="admin-logout-btn no-underline">
          Torna al Sito
        </Link>

        <button onClick={handleLogout} className="admin-logout-btn">
          <LogOut size={18} />
          Esci
        </button>
      </div>
    </div>
  );
}
