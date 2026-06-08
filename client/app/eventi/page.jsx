"use client";
import { safeJsonParse } from '@/utils/safeJson';
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { eventiData } from '@/data/eventi';
import AutoCarousel from '@/components/AutoCarousel';
import { usePathname } from 'next/navigation';

export default function Eventi() {
  const [eventi, setEventi] = React.useState([]);
  const [annuali, setAnnuali] = React.useState([]);
  const [loading, setLoading] = React.useState(true);
  const [selectedGallery, setSelectedGallery] = React.useState(null);
  const pathname = usePathname() || '';
  const isEn = pathname.startsWith('/en');

  React.useEffect(() => {
    const fetchData = async () => {
      try {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
        const [evRes, annRes] = await Promise.all([
          fetch(`${apiUrl}/events${isEn ? '?lang=en' : ''}`),
          fetch(`${apiUrl}/directory?category=eventi_annuali${isEn ? '&lang=en' : ''}`)
        ]);

        if (evRes.ok) {
          const data = await evRes.json();
          setEventi(data.events.filter(e => e.status === 'published'));
        }
        if (annRes.ok) {
          const annData = await annRes.json();
          setAnnuali(annData.items);
        }
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, [isEn]);

  const today = new Date();
  today.setHours(0, 0, 0, 0); // Consider today's events as future
  const futuri = eventi
    .filter(e => !e.start_date || new Date(e.start_date) >= today)
    .sort((a, b) => {
      if (!a.start_date) return 1;
      if (!b.start_date) return -1;
      return new Date(a.start_date) - new Date(b.start_date);
    });
  const passati = eventi
    .filter(e => e.start_date && new Date(e.start_date) < today)
    .sort((a, b) => new Date(b.start_date) - new Date(a.start_date));

  return (
    <StoreProvider>
      <Header />
      <SectionHero title={eventiData.hero.title} subtitle={eventiData.hero.subtitle} img={eventiData.hero.img} />
      
      {/* Eventi Annuali - Giant Cards Verticali (fissi per la tradizione) */}
      <section style={{ position: 'relative', padding: 'clamp(80px, 10vw, 140px) 0', background: 'var(--ink)' }}>
        <div className="ed-wrap" style={{ textAlign: 'center', marginBottom: 'clamp(50px, 8vw, 80px)', padding: '0 clamp(20px, 4.5vw, 64px)' }}>
          <span className="ed-subtitle">{isEn ? "Living Traditions" : "Tradizioni vive"}</span>
          <h2 className="ed-title" style={{ margin: 0 }}>{isEn ? "Annual Events" : "Eventi Annuali"}</h2>
        </div>

        <div 
          style={{ 
            display: 'grid', 
            gridTemplateColumns: 'repeat(auto-fit, minmax(400px, 1fr))',
            gap: 'clamp(30px, 4vw, 60px)', 
            padding: '0 clamp(20px, 4.5vw, 64px)',
            maxWidth: '1480px',
            margin: '0 auto'
          }}
        >
          {annuali.map((ev, index) => {
            const gallery = typeof ev.gallery === 'string' ? safeJsonParse(ev.gallery) : (ev.gallery || []);
            return (
              <div 
                key={ev.id} 
                style={{ 
                  width: '100%',
                  height: 'clamp(400px, 60vh, 550px)', 
                  position: 'relative',
                  display: 'flex',
                  alignItems: 'center',
                  borderRadius: '30px', 
                  overflow: 'hidden',
                  boxShadow: '0 25px 60px -15px rgba(0,0,0,0.7)',
                  border: '1px solid rgba(255,255,255,0.05)'
                }}
              >
                <div style={{ position: 'absolute', inset: 0, zIndex: 1 }}>
                  {gallery.length > 0 ? (
                    <AutoCarousel images={gallery} interval={4500 + index * 500} />
                  ) : (
                    <div style={{width: '100%', height: '100%', background: 'var(--stone)'}} />
                  )}
                </div>
                <div style={{ position: 'absolute', inset: 0, zIndex: 2, background: 'linear-gradient(to right, rgba(13,16,20,0.95) 0%, rgba(13,16,20,0.5) 60%, transparent 100%)' }} />
                <div style={{ position: 'relative', zIndex: 3, width: '100%', padding: '0 clamp(30px, 6vw, 80px)' }}>
                  <div style={{ maxWidth: '600px' }}>
                    <span style={{ color: 'var(--gold)', textTransform: 'uppercase', letterSpacing: '3px', fontSize: '0.85rem', fontWeight: 600, display: 'inline-block', marginBottom: '24px', borderBottom: '1px solid var(--gold)', paddingBottom: '6px' }}>
                      {ev.subtitle}
                    </span>
                    <h3 style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: 'clamp(2.5rem, 5vw, 4.5rem)', color: 'var(--paper)', margin: '0 0 24px', lineHeight: 1.05 }}>
                      {ev.title}
                    </h3>
                    <p style={{ color: 'rgba(244,239,229,0.85)', fontSize: '1.1rem', lineHeight: 1.8, margin: 0, whiteSpace: 'pre-wrap' }}>
                      {ev.description}
                    </p>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </section>

      {/* Eventi Dinamici (Stesso Formato di Eventi Annuali ma da DB) */}
      <section style={{ position: 'relative', padding: '0 0 clamp(80px, 10vw, 140px)', background: 'var(--ink)' }}>
        <div className="ed-wrap" style={{ textAlign: 'center', marginBottom: 'clamp(50px, 8vw, 80px)', padding: '0 clamp(20px, 4.5vw, 64px)' }}>
          <span className="ed-subtitle">{isEn ? "Upcoming" : "In Arrivo"}</span>
          <h2 className="ed-title" style={{ margin: 0 }}>{isEn ? `Events ${new Date().getFullYear()}` : `Eventi ${new Date().getFullYear()}`}</h2>
        </div>
        
        {loading ? (
          <div style={{ textAlign: 'center', padding: '100px', color: 'var(--stone)' }}>
            {isEn ? "Synchronizing events..." : "Sincronizzazione eventi in corso..."}
          </div>
        ) : futuri.length === 0 ? (
          <div style={{ textAlign: 'center', color: 'var(--stone)' }}>
            <p style={{ fontStyle: 'italic' }}>{isEn ? "No events scheduled yet." : "Nessun evento ancora inserito."}</p>
          </div>
        ) : (
          <div 
            style={{ 
              display: 'grid', 
              gridTemplateColumns: 'repeat(auto-fit, minmax(400px, 1fr))',
              gap: 'clamp(30px, 4vw, 60px)', 
              padding: '0 clamp(20px, 4.5vw, 64px)',
              maxWidth: '1480px',
              margin: '0 auto'
            }}
          >
            {futuri.map((ev, index) => {
              const gallery = typeof ev.gallery === 'string' ? safeJsonParse(ev.gallery) : (ev.gallery || []);
              return (
                <div 
                  key={ev.id} 
                  style={{ 
                    width: '100%',
                    height: 'clamp(400px, 60vh, 550px)', 
                    position: 'relative',
                    display: 'flex',
                    alignItems: 'center',
                    borderRadius: '30px', 
                    overflow: 'hidden',
                    boxShadow: '0 25px 60px -15px rgba(0,0,0,0.7)',
                    border: '1px solid rgba(255,255,255,0.05)'
                  }}
                >
                  <div style={{ position: 'absolute', inset: 0, zIndex: 1, backgroundColor: 'var(--ink)' }}>
                    {gallery.length > 0 && (
                      <div style={{ position: 'absolute', inset: 0, overflow: 'hidden' }}>
                        <img src={gallery[0]} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', filter: 'blur(30px) brightness(0.6)', transform: 'scale(1.2)' }} />
                      </div>
                    )}
                    {gallery.length > 0 ? (
                      <AutoCarousel images={gallery} interval={4500 + index * 500} objectFit="contain" />
                    ) : (
                      <div style={{width: '100%', height: '100%', background: 'var(--stone)'}} />
                    )}
                  </div>
                  <div style={{ position: 'absolute', inset: 0, zIndex: 2, background: 'linear-gradient(to right, rgba(13,16,20,0.95) 0%, rgba(13,16,20,0.5) 60%, transparent 100%)' }} />
                  <div style={{ position: 'relative', zIndex: 3, width: '100%', padding: '0 clamp(30px, 6vw, 80px)' }}>
                    <div style={{ maxWidth: '600px' }}>
                      <span style={{ color: 'var(--gold)', textTransform: 'uppercase', letterSpacing: '3px', fontSize: '0.85rem', fontWeight: 600, display: 'inline-block', marginBottom: '24px', borderBottom: '1px solid var(--gold)', paddingBottom: '6px' }}>
                        {ev.start_date ? new Date(ev.start_date).toLocaleDateString(isEn ? 'en-GB' : 'it-IT') : (isEn ? 'To be defined' : 'Da definire')}
                      </span>
                      <h3 style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: 'clamp(2.5rem, 5vw, 4.5rem)', color: 'var(--paper)', margin: '0 0 24px', lineHeight: 1.05 }}>
                        {ev.title}
                      </h3>
                      <div style={{ color: 'rgba(244,239,229,0.85)', fontSize: '1.1rem', lineHeight: 1.8, margin: 0, maxHeight: '80px', overflow: 'hidden', display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical' }} dangerouslySetInnerHTML={{ __html: ev.description }}></div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </section>

      {/* Calendario e Archivio (Dinamici) */}
      <section className="ed-sec alt">
        <div className="ed-wrap">
          {loading ? (
             <div style={{ textAlign: 'center', padding: '100px', color: 'var(--stone)' }}>
               {isEn ? "Synchronizing events..." : "Sincronizzazione eventi in corso..."}
             </div>
          ) : (
            <div style={{ display: 'flex', flexDirection: 'column', gap: '80px' }}>
              
              {/* Calendario Eventi Futuri */}
              <div>
                <span className="ed-subtitle">{isEn ? "Scheduled" : "In Programma"}</span>
                <h2 className="ed-title" style={{ fontSize: '3rem' }}>{isEn ? "Calendar" : "Calendario"}</h2>
                {futuri.length === 0 ? (
                  <p style={{ color: 'var(--stone)', fontStyle: 'italic' }}>{isEn ? "No upcoming events scheduled at the moment." : "Nessun evento futuro in programma al momento."}</p>
                ) : (
                  <div style={{ width: '100%', overflowX: 'auto' }}>
                    <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', marginTop: '20px' }}>
                      <thead>
                        <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.1)', color: 'var(--gold-soft)', fontSize: '0.85rem', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
                          <th style={{ padding: '16px 24px', width: '220px', fontWeight: 600 }}>{isEn ? "Date" : "Data"}</th>
                          <th style={{ padding: '16px 24px', fontWeight: 600 }}>{isEn ? "Title & Details" : "Titolo & Dettagli"}</th>
                        </tr>
                      </thead>
                      <tbody>
                        {futuri.map(cal => {
                          const galleryList = typeof cal.gallery === 'string' ? safeJsonParse(cal.gallery) : (cal.gallery || []);
                          const previewImage = galleryList.length > 0 ? galleryList[0] : null;
                          return (
                            <tr 
                              key={cal.id} 
                              style={{ borderBottom: '1px solid rgba(255,255,255,0.05)', transition: 'background 0.3s', cursor: galleryList.length > 0 ? 'pointer' : 'default' }} 
                              onMouseEnter={e => e.currentTarget.style.background = 'rgba(255,255,255,0.02)'} 
                              onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
                              onClick={() => {
                                if (galleryList.length > 0) setSelectedGallery(galleryList);
                              }}
                            >
                              <td style={{ padding: '16px 24px', verticalAlign: 'top', color: 'var(--stone)' }}>
                                <div style={{ fontSize: '1.1rem', fontWeight: 500, color: 'var(--paper)', whiteSpace: 'nowrap', paddingTop: '2px' }}>{cal.start_date ? new Date(cal.start_date).toLocaleDateString(isEn ? 'en-GB' : 'it-IT') : (isEn ? 'To be defined' : 'Da definire')}</div>
                                {cal.location && <div style={{ fontSize: '0.85rem', opacity: 0.7, marginTop: '8px' }}>{cal.location}</div>}
                              </td>
                              <td style={{ padding: '16px 24px', verticalAlign: 'top' }}>
                                <h4 style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: '1.7rem', color: 'var(--gold)', margin: '0 0 8px', fontWeight: 500, lineHeight: 1.1 }}>{cal.title}</h4>
                                <div 
                                  className="ql-editor-content"
                                  style={{ opacity: 0.85, fontSize: '1rem', lineHeight: 1.6 }}
                                  dangerouslySetInnerHTML={{ __html: cal.description }} 
                                />
                              </td>
                            </tr>
                          );
                        })}
                      </tbody>
                    </table>
                  </div>
                )}
              </div>

            </div>
          )}
        </div>
      </section>

      <Footer />
      <ClientScripts />

      {/* Modale Galleria Foto */}
      {selectedGallery && (
        <div 
          style={{
            position: 'fixed',
            inset: 0,
            zIndex: 9999,
            backgroundColor: 'rgba(0,0,0,0.92)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: 'clamp(20px, 4vw, 60px)'
          }}
          onClick={() => setSelectedGallery(null)}
        >
          {/* Pulsante di chiusura */}
          <button 
            onClick={(e) => { e.stopPropagation(); setSelectedGallery(null); }}
            style={{
              position: 'absolute',
              top: '20px',
              right: '20px',
              background: 'rgba(255,255,255,0.1)',
              border: 'none',
              borderRadius: '50%',
              width: '40px',
              height: '40px',
              color: 'var(--paper)',
              fontSize: '1.5rem',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              zIndex: 10000,
              transition: 'background 0.3s'
            }}
            onMouseEnter={e => e.currentTarget.style.background = 'rgba(255,255,255,0.2)'}
            onMouseLeave={e => e.currentTarget.style.background = 'rgba(255,255,255,0.1)'}
          >
            &times;
          </button>
          
          <div 
            style={{ width: '100%', maxWidth: '1200px', height: '100%', maxHeight: '90vh', position: 'relative' }}
            onClick={(e) => e.stopPropagation()} // Previene la chiusura cliccando sulla foto
          >
            {selectedGallery.length > 1 ? (
              <AutoCarousel images={selectedGallery} interval={4500} objectFit="contain" />
            ) : (
              <img src={selectedGallery[0]} alt="Evento" style={{ width: '100%', height: '100%', objectFit: 'contain' }} />
            )}
          </div>
        </div>
      )}
    </StoreProvider>
  );
}
