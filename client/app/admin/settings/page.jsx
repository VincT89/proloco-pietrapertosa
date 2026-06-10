"use client";

import React, { useState } from 'react';
import { Save, Lock, Mail } from 'lucide-react';
import Button from '../components/ui/Button';
import Card from '../components/ui/Card';
import Input from '../components/ui/Input';

export default function AdminSettings() {
  const [formData, setFormData] = useState({
    currentPassword: '',
    newEmail: '',
    newPassword: '',
    confirmPassword: ''
  });
  
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess('');

    if (formData.newPassword && formData.newPassword !== formData.confirmPassword) {
      setError('Le nuove password non coincidono');
      setLoading(false);
      return;
    }

    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
      const token = localStorage.getItem('admin_token');
      
      const payload = {
        currentPassword: formData.currentPassword,
        newEmail: formData.newEmail || undefined,
        newPassword: formData.newPassword || undefined
      };

      const res = await fetch(`${apiUrl}/auth/settings`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(payload)
      });

      if (!res.ok) {
        const errData = await res.json();
        throw new Error(errData.error || "Errore durante l'aggiornamento");
      }

      setSuccess("Impostazioni aggiornate con successo! Se hai cambiato l'email, ricordati di usarla al prossimo accesso.");
      setFormData({ currentPassword: '', newEmail: '', newPassword: '', confirmPassword: '' });
      
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="admin-page-container-small">
      <div className="admin-page-header">
        <div>
          <h1 className="admin-page-title">Impostazioni Account</h1>
          <p className="admin-page-subtitle mt-8px">Modifica la tua email o la tua password di accesso.</p>
        </div>
      </div>

      {error && <div className="admin-media-error">{error}</div>}
      {success && <div className="admin-media-success">{success}</div>}

      <form onSubmit={handleSubmit}>
        <Card>
          <div className="flex-col">
            <div className="admin-form-section">
              <Input 
                label="Password Attuale *"
                type="password" 
                name="currentPassword"
                value={formData.currentPassword}
                onChange={handleChange}
                placeholder="Inserisci la password attuale"
                required 
              />
              <p className="admin-list-subtext mt-8px">
                Per motivi di sicurezza, devi inserire la tua password attuale per effettuare qualsiasi modifica.
              </p>
            </div>

            <div className="grid-2">
              <Input 
                label="Nuova Email"
                type="email" 
                name="newEmail"
                value={formData.newEmail}
                onChange={handleChange}
                placeholder="Lascia vuoto per non modificare l'email"
              />
              <Input 
                label="Nuova Password"
                type="password" 
                name="newPassword"
                value={formData.newPassword}
                onChange={handleChange}
                placeholder="Lascia vuoto per non modificare"
              />
            </div>

            {formData.newPassword && (
              <div className="mt-16px max-w-50">
                <Input 
                  label="Conferma Nuova Password"
                  type="password" 
                  name="confirmPassword"
                  value={formData.confirmPassword}
                  onChange={handleChange}
                  placeholder="Ripeti la nuova password"
                  required
                />
              </div>
            )}
          </div>
        </Card>

        <div className="flex-row mt-24px">
          <Button type="submit" disabled={loading || !formData.currentPassword} icon={<Save size={18} />}>
            {loading ? 'Salvataggio...' : 'Salva Impostazioni'}
          </Button>
        </div>
      </form>
    </div>
  );
}
