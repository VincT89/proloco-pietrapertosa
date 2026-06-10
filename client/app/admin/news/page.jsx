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

        <Link href="/admin/news/edit/new" className="no-underline">
          <Button icon={<Plus size={16} />}>Scrivi Notizia</Button>
        </Link>
      </div>

      {loading ? (
        <div className="admin-loading-text">Caricamento in corso...</div>
      ) : news.length === 0 ? (
        <Card className="admin-media-empty">
          <FileText size={48} className="admin-media-empty-icon" />
          <h3 className="admin-media-empty-title">Nessuna notizia pubblicata</h3>
          <p className="admin-media-empty-text">Clicca su "Scrivi Notizia" in alto a destra per iniziare.</p>
        </Card>
      ) : (
        <Table
          columns={tableColumns}
          data={news}
          renderRow={(item) => (
            <tr key={item.id}>
              <td className="w-100px">
                {item.cover_image_url ? (
                  // eslint-disable-next-line @next/next/no-img-element
                  <img src={item.cover_image_url} alt="Cover" className="admin-table-thumb" />
                ) : (
                  <div className="admin-table-thumb-placeholder">
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
                <span className={`admin-badge-status ${item.status === 'published' ? 'published' : 'draft'}`}>
                  {item.status === 'published' ? 'Pubblicato' : 'Bozza'}
                </span>
              </td>
              <td className="text-right">
                <div className="flex-row justify-end gap-8px">
                  <Link href={`/admin/news/edit/${item.id}`} className="no-underline">
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
