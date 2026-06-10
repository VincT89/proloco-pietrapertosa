"use client";

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { Plus, Edit2, Trash2, Calendar } from 'lucide-react';
import Button from '../components/ui/Button';
import Card from '../components/ui/Card';
import Table from '../components/ui/Table';

export default function EventsAdminPage() {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchEvents = async () => {
    try {
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/events`);
      if (res.ok) {
        const data = await res.json();
        setEvents(data.events);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchEvents();
  }, []);

  const handleDelete = async (id) => {
    if (!confirm("Sei sicuro di voler eliminare questo evento?")) return;
    try {
      const token = localStorage.getItem('admin_token');
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/events/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` }
      });
      if (res.ok) {
        fetchEvents();
      } else {
        alert("Errore nell'eliminazione dell'evento.");
      }
    } catch (err) {
      console.error(err);
    }
  };

  const tableColumns = [
    { label: 'Locandina', width: '100px' },
    { label: 'Titolo (IT)' },
    { label: 'Data' },
    { label: 'Luogo' },
    { label: 'Stato' },
    { label: 'Azioni', align: 'right' }
  ];

  return (
    <div>
      <div className="admin-page-header">
        <div>
          <h1 className="admin-page-title">Eventi <span>programmazione</span></h1>
        </div>

        <Link href="/admin/events/edit/new" className="no-underline">
          <Button icon={<Plus size={16} />}>Nuovo Evento</Button>
        </Link>
      </div>

      {loading ? (
        <div className="admin-loading-text">Caricamento in corso...</div>
      ) : events.length === 0 ? (
        <Card className="admin-media-empty">
          <Calendar size={48} className="admin-media-empty-icon" />
          <h3 className="admin-media-empty-title">Nessun evento in programma</h3>
          <p className="admin-media-empty-text">Clicca su "Nuovo Evento" in alto a destra per iniziare a pianificare.</p>
        </Card>
      ) : (
        <Table
          columns={tableColumns}
          data={events}
          renderRow={(item) => (
            <tr key={item.id}>
              <td className="w-100px">
                {item.cover_image_url ? (
                  // eslint-disable-next-line @next/next/no-img-element
                  <img src={item.cover_image_url} alt="Cover" className="admin-table-thumb" />
                ) : (
                  <div className="admin-table-thumb-placeholder">
                    <Calendar size={16} color="var(--admin-muted)" />
                  </div>
                )}
              </td>
              <td>
                <strong>{item.title}</strong>
              </td>
              <td>
                {item.start_date ? new Date(item.start_date).toLocaleDateString('it-IT') : 'Da definire'}
              </td>
              <td>
                {item.location || 'Da definire'}
              </td>
              <td>
                <span className={`admin-badge-status ${item.status === 'published' ? 'published' : 'draft'}`}>
                  {item.status === 'published' ? 'Pubblicato' : 'Bozza'}
                </span>
              </td>
              <td className="text-right">
                <div className="flex-row justify-end gap-8px">
                  <Link href={`/admin/events/edit/${item.id}`} className="no-underline">
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
