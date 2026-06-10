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

  if (!user) return <div style={{ color: 'var(--admin-muted)' }}>Caricamento...</div>;

  return (
    <div style={{ maxWidth: '1400px' }}>
      
      <div className="admin-page-header">
        <div>
          <h1 className="admin-page-title" style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: '2.5rem' }}>
            Benvenuto, <span>{user.name}</span>
          </h1>
          <p style={{ color: 'var(--admin-muted)', marginTop: '8px' }}>
            Pannello di controllo ufficiale della Pro Loco di Pietrapertosa.
          </p>
        </div>
      </div>

      {loading ? (
        <div style={{ color: 'var(--admin-muted)' }}>Calcolo statistiche in corso...</div>
      ) : (
        <>
          <h2 style={{ fontSize: '1rem', color: 'var(--admin-muted)', marginBottom: '16px', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
            Riepilogo Contenuti
          </h2>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '20px', marginBottom: '40px' }}>
            
            <Card style={{ margin: 0 }}>
              <div className="flex-row" style={{ gap: '20px' }}>
                <div style={{ background: 'rgba(184, 135, 70, 0.1)', padding: '16px', borderRadius: '50%', color: 'var(--admin-accent)' }}>
                  <FileText size={28} />
                </div>
                <div>
                  <div style={{ fontSize: '2.2rem', fontWeight: 'bold', color: 'var(--admin-ink)', lineHeight: 1 }}>{stats.news}</div>
                  <div style={{ color: 'var(--admin-muted)', fontSize: '0.85rem', marginTop: '4px' }}>Notizie Pubblicate</div>
                </div>
              </div>
            </Card>

            <Card style={{ margin: 0 }}>
              <div className="flex-row" style={{ gap: '20px' }}>
                <div style={{ background: 'rgba(184, 135, 70, 0.1)', padding: '16px', borderRadius: '50%', color: 'var(--admin-accent)' }}>
                  <Calendar size={28} />
                </div>
                <div>
                  <div style={{ fontSize: '2.2rem', fontWeight: 'bold', color: 'var(--admin-ink)', lineHeight: 1 }}>{stats.events}</div>
                  <div style={{ color: 'var(--admin-muted)', fontSize: '0.85rem', marginTop: '4px' }}>Eventi in Calendario</div>
                </div>
              </div>
            </Card>

            <Card style={{ margin: 0 }}>
              <div className="flex-row" style={{ gap: '20px' }}>
                <div style={{ background: 'rgba(184, 135, 70, 0.1)', padding: '16px', borderRadius: '50%', color: 'var(--admin-accent)' }}>
                  <LayoutDashboard size={28} />
                </div>
                <div>
                  <div style={{ fontSize: '2.2rem', fontWeight: 'bold', color: 'var(--admin-ink)', lineHeight: 1 }}>{stats.directory}</div>
                  <div style={{ color: 'var(--admin-muted)', fontSize: '0.85rem', marginTop: '4px' }}>Attività e Associazioni</div>
                </div>
              </div>
            </Card>

            <Card style={{ margin: 0 }}>
              <div className="flex-row" style={{ gap: '20px' }}>
                <div style={{ background: 'rgba(184, 135, 70, 0.1)', padding: '16px', borderRadius: '50%', color: 'var(--admin-accent)' }}>
                  <ImageIcon size={28} />
                </div>
                <div>
                  <div style={{ fontSize: '2.2rem', fontWeight: 'bold', color: 'var(--admin-ink)', lineHeight: 1 }}>{stats.media}</div>
                  <div style={{ color: 'var(--admin-muted)', fontSize: '0.85rem', marginTop: '4px' }}>Foto/Video in Cloud</div>
                </div>
              </div>
            </Card>

          </div>

          <h2 style={{ fontSize: '1rem', color: 'var(--admin-muted)', marginBottom: '16px', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
            Azioni Rapide
          </h2>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px', marginBottom: '40px' }}>
            
            <Link href="/admin/news/edit/new" style={{ display: 'flex', alignItems: 'center', gap: '16px', background: 'var(--admin-accent)', color: 'white', padding: '20px', borderRadius: 'var(--admin-radius)', textDecoration: 'none', fontWeight: 'bold', transition: 'transform 0.2s', boxShadow: 'var(--admin-shadow)' }}>
              <div style={{ background: 'rgba(0,0,0,0.1)', padding: '10px', borderRadius: '50%' }}><Plus size={24} /></div>
              <span>Scrivi un Avviso / Notizia</span>
            </Link>

            <Link href="/admin/events/edit/new" style={{ display: 'flex', alignItems: 'center', gap: '16px', background: 'var(--admin-panel)', border: '1px solid var(--admin-border)', color: 'var(--admin-accent)', padding: '20px', borderRadius: 'var(--admin-radius)', textDecoration: 'none', fontWeight: 'bold', transition: 'transform 0.2s', boxShadow: 'var(--admin-shadow)' }}>
              <div style={{ background: 'rgba(184, 135, 70, 0.1)', padding: '10px', borderRadius: '50%' }}><Plus size={24} /></div>
              <span>Programma un Evento</span>
            </Link>

            <Link href="/admin/media" style={{ display: 'flex', alignItems: 'center', gap: '16px', background: 'var(--admin-panel)', border: '1px solid var(--admin-border)', color: 'var(--admin-ink)', padding: '20px', borderRadius: 'var(--admin-radius)', textDecoration: 'none', fontWeight: 'bold', transition: 'transform 0.2s', boxShadow: 'var(--admin-shadow)' }}>
              <div style={{ background: 'var(--admin-bg)', padding: '10px', borderRadius: '50%' }}><ImageIcon size={24} /></div>
              <span>Carica Foto nella Libreria</span>
            </Link>

          </div>

          <h2 style={{ fontSize: '1rem', color: 'var(--admin-muted)', marginBottom: '16px', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
            Ultimi Elementi Inseriti
          </h2>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(350px, 1fr))', gap: '24px' }}>
            
            {/* Ultime Notizie */}
            <Card style={{ margin: 0 }}>
              <h3 style={{ fontSize: '1.1rem', color: 'var(--admin-ink)', marginBottom: '20px', borderBottom: '1px solid var(--admin-border)', paddingBottom: '12px', margin: '-4px -4px 16px' }}>Ultime Notizie</h3>
              {stats.latestNews && stats.latestNews.length > 0 ? (
                <div className="flex-col" style={{ gap: '16px' }}>
                  {stats.latestNews.map(item => (
                    <div key={item.id} className="flex-row" style={{ justifyContent: 'space-between' }}>
                      <span style={{ color: 'var(--admin-ink)', fontSize: '0.95rem' }}>{item.title}</span>
                      <Link href={`/admin/news/edit/${item.id}`} style={{ color: 'var(--admin-accent)', fontSize: '0.8rem', textDecoration: 'none', fontWeight: 600 }}>Modifica</Link>
                    </div>
                  ))}
                </div>
              ) : <div style={{ color: 'var(--admin-muted)' }}>Nessuna notizia</div>}
            </Card>

            {/* Ultimi Eventi */}
            <Card style={{ margin: 0 }}>
              <h3 style={{ fontSize: '1.1rem', color: 'var(--admin-ink)', marginBottom: '20px', borderBottom: '1px solid var(--admin-border)', paddingBottom: '12px', margin: '-4px -4px 16px' }}>Ultimi Eventi</h3>
              {stats.latestEvents && stats.latestEvents.length > 0 ? (
                <div className="flex-col" style={{ gap: '16px' }}>
                  {stats.latestEvents.map(item => (
                    <div key={item.id} className="flex-row" style={{ justifyContent: 'space-between' }}>
                      <span style={{ color: 'var(--admin-ink)', fontSize: '0.95rem' }}>{item.title}</span>
                      <Link href={`/admin/events/edit/${item.id}`} style={{ color: 'var(--admin-accent)', fontSize: '0.8rem', textDecoration: 'none', fontWeight: 600 }}>Modifica</Link>
                    </div>
                  ))}
                </div>
              ) : <div style={{ color: 'var(--admin-muted)' }}>Nessun evento</div>}
            </Card>

            {/* Ultime Directory */}
            <Card style={{ margin: 0 }}>
              <h3 style={{ fontSize: '1.1rem', color: 'var(--admin-ink)', marginBottom: '20px', borderBottom: '1px solid var(--admin-border)', paddingBottom: '12px', margin: '-4px -4px 16px' }}>Ultime Attività e Territorio</h3>
              {stats.latestDir && stats.latestDir.length > 0 ? (
                <div className="flex-col" style={{ gap: '16px' }}>
                  {stats.latestDir.map(item => (
                    <div key={item.id} className="flex-row" style={{ justifyContent: 'space-between' }}>
                      <div className="flex-col" style={{ gap: '2px' }}>
                        <span style={{ color: 'var(--admin-ink)', fontSize: '0.95rem' }}>{item.title}</span>
                        <span style={{ color: 'var(--admin-muted)', fontSize: '0.75rem' }}>{item.category.replace('_', ' ')}</span>
                      </div>
                      <Link href={`/admin/directory/edit/${item.id}`} style={{ color: 'var(--admin-accent)', fontSize: '0.8rem', textDecoration: 'none', fontWeight: 600 }}>Modifica</Link>
                    </div>
                  ))}
                </div>
              ) : <div style={{ color: 'var(--admin-muted)' }}>Nessuna attività</div>}
            </Card>

          </div>
        </>
      )}

    </div>
  );
}
