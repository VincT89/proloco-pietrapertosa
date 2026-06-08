"use client";

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import { Lock } from 'lucide-react';
import Card from '../components/ui/Card';
import Input from '../components/ui/Input';
import Button from '../components/ui/Button';

export default function AdminLogin() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  const handleLogin = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/auth/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });

      const data = await res.json();

      if (!res.ok) {
        throw new Error(data.error || 'Errore durante il login');
      }

      // Salva il token
      localStorage.setItem('admin_token', data.token);
      localStorage.setItem('admin_user', JSON.stringify(data.user));

      // Reindirizza alla dashboard
      router.push('/admin/dashboard');

    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ minHeight: '100vh', width: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 'clamp(10px, 5vw, 20px)', boxSizing: 'border-box' }}>
      <div style={{ maxWidth: '400px', width: '100%' }}>
        <Card>
          <div style={{ textAlign: 'center', marginBottom: '24px' }}>
            <div style={{ width: '60px', height: '60px', background: 'var(--admin-accent)', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px', color: '#fff' }}>
              <Lock size={30} />
            </div>
            <h1 className="admin-page-title" style={{ fontSize: '1.75rem', marginBottom: '8px' }}>Accesso Riservato</h1>
            <p style={{ color: 'var(--admin-muted)', fontSize: '0.9rem' }}>Area di amministrazione Pro Loco</p>
          </div>

          {error && (
            <div style={{ background: 'rgba(255,0,0,0.05)', border: '1px solid rgba(255,0,0,0.1)', color: '#d32f2f', padding: '12px', borderRadius: '4px', marginBottom: '24px', fontSize: '0.9rem', textAlign: 'center' }}>
              {error}
            </div>
          )}

          <form onSubmit={handleLogin} className="flex-col">
            <Input 
              label="Email"
              type="email" 
              name="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />

            <Input 
              label="Password"
              type="password" 
              name="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />

            <div style={{ marginTop: '16px' }}>
              <Button type="submit" disabled={loading} style={{ width: '100%', justifyContent: 'center', padding: '14px' }}>
                {loading ? 'Accesso in corso...' : 'Entra nel Pannello'}
              </Button>
            </div>
          </form>
        </Card>
      </div>
    </div>
  );
}
