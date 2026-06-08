"use client";

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { Plus, Edit2, Trash2, FileText } from 'lucide-react';
import Button from '../components/ui/Button';
import Card from '../components/ui/Card';
import Table from '../components/ui/Table';

export default function NewsAdminPage() {
  const [news, setNews] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchNews = async () => {
    try {
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/news`);
      if (res.ok) {
        const data = await res.json();
        setNews(data.news);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchNews();
  }, []);

  const handleDelete = async (id) => {
    if (!confirm("Sei sicuro di voler eliminare questa notizia?")) return;
    try {
      const token = localStorage.getItem('admin_token');
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/news/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` }
      });
      if (res.ok) {
        fetchNews();
      } else {
        alert("Errore nell'eliminazione della notizia.");
      }
    } catch (err) {
      console.error(err);
    }
  };

  const tableColumns = [
    { label: 'Copertina', width: '100px' },
    { label: 'Titolo (IT)' },
    { label: 'Data' },
    { label: 'Stato' },
    { label: 'Azioni', align: 'right' }
  ];

  return (
    <div>
      <div className="admin-page-header">
        <div>
          <h1 className="admin-page-title">Notizie <span>gestione articoli</span></h1>
        </div>

        <Link href="/admin/news/edit/new" style={{ textDecoration: 'none' }}>
          <Button icon={<Plus size={16} />}>Scrivi Notizia</Button>
        </Link>
      </div>

      {loading ? (
        <div style={{ textAlign: 'center', color: 'var(--admin-muted)', padding: '40px' }}>Caricamento in corso...</div>
      ) : news.length === 0 ? (
        <Card style={{ textAlign: 'center', padding: '80px 40px' }}>
          <FileText size={48} color="var(--admin-muted)" style={{ margin: '0 auto 16px', opacity: 0.5 }} />
          <h3 style={{ color: 'var(--admin-ink)', fontSize: '1.2rem', marginBottom: '8px' }}>Nessuna notizia pubblicata</h3>
          <p style={{ color: 'var(--admin-muted)' }}>Clicca su "Scrivi Notizia" in alto a destra per iniziare.</p>
        </Card>
      ) : (
        <Table
          columns={tableColumns}
          data={news}
          renderRow={(item) => (
            <tr key={item.id}>
              <td style={{ width: '100px' }}>
                {item.cover_image_url ? (
                  // eslint-disable-next-line @next/next/no-img-element
                  <img src={item.cover_image_url} alt="Cover" style={{ width: '80px', height: '50px', objectFit: 'cover', borderRadius: '4px' }} />
                ) : (
                  <div style={{ width: '80px', height: '50px', background: 'var(--admin-bg)', borderRadius: '4px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    <FileText size={16} color="var(--admin-muted)" />
                  </div>
                )}
              </td>
              <td>
                <strong>{item.title}</strong>
              </td>
              <td>
                {new Date(item.created_at).toLocaleDateString('it-IT')}
              </td>
              <td>
                <span style={{ 
                  padding: '4px 8px', borderRadius: '4px', fontSize: '0.75rem', fontWeight: 'bold',
                  background: item.status === 'published' ? '#dcfce7' : '#fef3c7',
                  color: item.status === 'published' ? '#166534' : '#92400e' 
                }}>
                  {item.status === 'published' ? 'Pubblicato' : 'Bozza'}
                </span>
              </td>
              <td style={{ textAlign: 'right' }}>
                <div className="flex-row" style={{ justifyContent: 'flex-end', gap: '8px' }}>
                  <Link href={`/admin/news/edit/${item.id}`} style={{ textDecoration: 'none' }}>
                    <Button variant="ghost" icon={<Edit2 size={16} />} />
                  </Link>
                  <Button variant="ghost" icon={<Trash2 size={16} color="#ef4444" />} onClick={() => handleDelete(item.id)} />
                </div>
              </td>
            </tr>
          )}
        />
      )}
    </div>
  );
}
