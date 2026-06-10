"use client";

import React from 'react';
import { usePathname } from 'next/navigation';
import '@/app/globals.css';
import './admin.css';
import Sidebar from './components/Sidebar';
import AdminGuard from './components/AdminGuard';

export default function AdminLayout({ children }) {
  const pathname = usePathname();
  const isLoginPage = pathname === '/admin/login';

  if (isLoginPage) {
    return (
      <div className="admin-body admin-layout">
        <main className="admin-login-main">
          {children}
        </main>
      </div>
    );
  }

  return (
    <div className="admin-body admin-layout">
      <AdminGuard>
        <Sidebar />
        <main className="admin-main">
          {children}
        </main>
      </AdminGuard>
    </div>
  );
}
