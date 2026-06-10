"use client";
import { safeJsonParse } from '@/utils/safeJson';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { FileText, Calendar, LayoutDashboard, ImageIcon, Plus } from 'lucide-react';
import Card from '../components/ui/Card';

export default function AdminDashboard() {
  const router = useRouter();
  const [user, setUser] = useState(null);
  
  const [stats, setStats] = useState({
    news: 0,
    events: 0,
    directory: 0,
    media: 0
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem('admin_token');
    const userData = localStorage.getItem('admin_user');

    if (!token || !userData) {
      router.push('/admin/login');
      return;
    } 
    setUser(safeJsonParse(userData));

    const fetchStats = async () => {
      try {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
        const headers = { 'Authorization': `Bearer ${token}` };
        
        const [newsRes, eventsRes, dirRes, mediaRes] = await Promise.all([
          fetch(`${apiUrl}/news`, { headers }),
          fetch(`${apiUrl}/events`, { headers }),
          fetch(`${apiUrl}/directory`, { headers }),
          fetch(`${apiUrl}/media`, { headers })
        ]);

        const newsData = newsRes.ok ? await newsRes.json() : { news: [] };
        const eventsData = eventsRes.ok ? await eventsRes.json() : { events: [] };
        const dirData = dirRes.ok ? await dirRes.json() : { items: [] };
        const mediaData = mediaRes.ok ? await mediaRes.json() : { media: [] };

        const sortedNews = [...newsData.news].sort((a,b) => b.id - a.id).slice(0, 3);
        const sortedEvents = [...eventsData.events].sort((a,b) => b.id - a.id).slice(0, 3);
        const sortedDir = [...dirData.items].sort((a,b) => b.id - a.id).slice(0, 5);

        setStats({
          news: newsData.news.length,
          events: eventsData.events.length,
          directory: dirData.items.length,
          media: mediaData.media.length,
          latestNews: sortedNews,
          latestEvents: sortedEvents,
          latestDir: sortedDir
        });
      } catch (err) {
        console.error("Errore nel recupero statistiche", err);
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, [router]);

  if (!user) return <div className="admin-loading-text">Caricamento...</div>;

  return (
    <div className="admin-page-container">
      
      <div className="admin-page-header">
        <div>
          <h1 className="admin-page-title admin-dashboard-title">
            Benvenuto, <span>{user.name}</span>
          </h1>
          <p className="admin-page-subtitle">
            Pannello di controllo ufficiale della Pro Loco di Pietrapertosa.
          </p>
        </div>
      </div>

      {loading ? (
        <div className="admin-loading-text">Calcolo statistiche in corso...</div>
      ) : (
        <>
          <h2 className="admin-section-title">
            Riepilogo Contenuti
          </h2>
          <div className="admin-stats-grid">
            
            <Card className="admin-list-card">
              <div className="flex-row">
                <div className="admin-stat-icon">
                  <FileText size={28} />
                </div>
                <div>
                  <div className="admin-stat-value">{stats.news}</div>
                  <div className="admin-stat-label">Notizie Pubblicate</div>
                </div>
              </div>
            </Card>

            <Card className="admin-list-card">
              <div className="flex-row">
                <div className="admin-stat-icon">
                  <Calendar size={28} />
                </div>
                <div>
                  <div className="admin-stat-value">{stats.events}</div>
                  <div className="admin-stat-label">Eventi in Calendario</div>
                </div>
              </div>
            </Card>

            <Card className="admin-list-card">
              <div className="flex-row">
                <div className="admin-stat-icon">
                  <LayoutDashboard size={28} />
                </div>
                <div>
                  <div className="admin-stat-value">{stats.directory}</div>
                  <div className="admin-stat-label">Attività e Associazioni</div>
                </div>
              </div>
            </Card>

            <Card className="admin-list-card">
              <div className="flex-row">
                <div className="admin-stat-icon">
                  <ImageIcon size={28} />
                </div>
                <div>
                  <div className="admin-stat-value">{stats.media}</div>
                  <div className="admin-stat-label">Foto/Video in Cloud</div>
                </div>
              </div>
            </Card>

          </div>

          <h2 className="admin-section-title">
            Azioni Rapide
          </h2>
          <div className="admin-actions-grid">
            
            <Link href="/admin/news/edit/new" className="admin-action-card primary">
              <div className="icon-wrap"><Plus size={24} /></div>
              <span>Scrivi un Avviso / Notizia</span>
            </Link>

            <Link href="/admin/events/edit/new" className="admin-action-card secondary">
              <div className="icon-wrap"><Plus size={24} /></div>
              <span>Programma un Evento</span>
            </Link>

            <Link href="/admin/media" className="admin-action-card tertiary">
              <div className="icon-wrap"><ImageIcon size={24} /></div>
              <span>Carica Foto nella Libreria</span>
            </Link>

          </div>

          <h2 className="admin-section-title">
            Ultimi Elementi Inseriti
          </h2>
          <div className="admin-lists-grid">
            
            {/* Ultime Notizie */}
            <Card className="admin-list-card">
              <h3 className="admin-list-title">Ultime Notizie</h3>
              {stats.latestNews && stats.latestNews.length > 0 ? (
                <div className="flex-col">
                  {stats.latestNews.map(item => (
                    <div key={item.id} className="admin-list-row">
                      <span className="admin-list-text">{item.title}</span>
                      <Link href={`/admin/news/edit/${item.id}`} className="admin-list-link">Modifica</Link>
                    </div>
                  ))}
                </div>
              ) : <div className="admin-loading-text">Nessuna notizia</div>}
            </Card>

            {/* Ultimi Eventi */}
            <Card className="admin-list-card">
              <h3 className="admin-list-title">Ultimi Eventi</h3>
              {stats.latestEvents && stats.latestEvents.length > 0 ? (
                <div className="flex-col">
                  {stats.latestEvents.map(item => (
                    <div key={item.id} className="admin-list-row">
                      <span className="admin-list-text">{item.title}</span>
                      <Link href={`/admin/events/edit/${item.id}`} className="admin-list-link">Modifica</Link>
                    </div>
                  ))}
                </div>
              ) : <div className="admin-loading-text">Nessun evento</div>}
            </Card>

            {/* Ultime Directory */}
            <Card className="admin-list-card">
              <h3 className="admin-list-title">Ultime Attività e Territorio</h3>
              {stats.latestDir && stats.latestDir.length > 0 ? (
                <div className="flex-col">
                  {stats.latestDir.map(item => (
                    <div key={item.id} className="admin-list-row">
                      <div className="flex-col gap-2px">
                        <span className="admin-list-text">{item.title}</span>
                        <span className="admin-list-subtext">{item.category.replace('_', ' ')}</span>
                      </div>
                      <Link href={`/admin/directory/edit/${item.id}`} className="admin-list-link">Modifica</Link>
                    </div>
                  ))}
                </div>
              ) : <div className="admin-loading-text">Nessuna attività</div>}
            </Card>

          </div>
        </>
      )}

    </div>
  );
}
