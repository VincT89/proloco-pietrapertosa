"use client";

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { Plus, Edit2, Trash2 } from 'lucide-react';
import Button from '../components/ui/Button';

export default function AdminGallery() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchItems = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
      const res = await fetch(`${apiUrl}/gallery`);
      if (!res.ok) throw new Error('Errore nel caricamento della galleria');
      const data = await res.json();
      setItems(data.items || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchItems();
  }, []);

  const handleDelete = async (id) => {
    if (!window.confirm('Sei sicuro di voler eliminare questa sezione della galleria?')) return;
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
      const token = localStorage.getItem('admin_token');
      const res = await fetch(`${apiUrl}/gallery/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });
      if (res.ok) {
        fetchItems();
      } else {
        alert("Errore durante l'eliminazione");
      }
    } catch (err) {
      alert(err.message);
    }
  };

  if (loading) return <div style={{ padding: '40px', color: 'white' }}>Caricamento...</div>;

  return (
    <div style={{ width: '100%' }}>
      <div className="admin-page-header">
        <div>
          <h1 className="admin-page-title">Gestione Galleria</h1>
          <p style={{ color: 'var(--admin-muted)', marginTop: '8px' }}>Crea sezioni (album) e aggiungi immagini dalla libreria.</p>
        </div>
        <Link href="/admin/gallery/edit/new" style={{ textDecoration: 'none' }}>
          <Button icon={<Plus size={16} />}>Crea Sezione</Button>
        </Link>
      </div>

      {error && <div className="admin-error">{error}</div>}

      <div className="admin-table-container">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Titolo Sezione (IT)</th>
              <th>Numero Media</th>
              <th>Data</th>
              <th className="actions-col" style={{ textAlign: 'right' }}>Azioni</th>
            </tr>
          </thead>
          <tbody>
            {items.length === 0 ? (
              <tr>
                <td colSpan="4" style={{ textAlign: 'center', padding: '30px', color: 'var(--admin-muted)' }}>
                  Nessuna sezione presente nella galleria. Clicca su "Crea Sezione" per iniziare.
                </td>
              </tr>
            ) : (
              items.map(item => {
                const dateObj = item.section_date ? new Date(item.section_date) : null;
                const formattedDate = dateObj ? `${dateObj.getMonth()+1}/${dateObj.getFullYear()}` : '-';
                const mediaCount = item.media_urls ? item.media_urls.length : 0;

                return (
                  <tr key={item.id}>
                    <td style={{ fontWeight: '500' }}>{item.title || 'Senza Titolo'}</td>
                    <td>{mediaCount} {mediaCount === 1 ? 'Foto' : 'Foto'}</td>
                    <td>{formattedDate}</td>
                    <td className="actions-cell" style={{ textAlign: 'right' }}>
                      <div className="flex-row" style={{ justifyContent: 'flex-end', gap: '8px' }}>
                        <Link href={`/admin/gallery/edit/${item.id}`} style={{ textDecoration: 'none' }} title="Modifica">
                          <Button variant="ghost" icon={<Edit2 size={16} />} />
                        </Link>
                        <Button variant="ghost" icon={<Trash2 size={16} color="#ef4444" />} onClick={() => handleDelete(item.id)} title="Elimina" />
                      </div>
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
