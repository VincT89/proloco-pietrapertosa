"use client";
import { safeJsonParse } from '@/utils/safeJson';
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { homeData } from '@/data/home';
import { useSearchParams } from 'next/navigation';
import { getLang } from '@/utils/lang';

export default function Notizie() {
  const [notizie, setNotizie] = React.useState([]);
  const [loading, setLoading] = React.useState(true);
  const searchParams = useSearchParams();
  const lang = getLang(searchParams);
  const isEn = lang === 'en';

  React.useEffect(() => {
    const fetchNotizie = async () => {
      try {
        const url = `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/news${isEn ? '?lang=en' : ''}`;
        const res = await fetch(url);
        if (res.ok) {
          const data = await res.json();
          // Mostra solo quelle pubblicate
          setNotizie(data.news.filter(n => n.status === 'published'));
        }
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    };
    fetchNotizie();
  }, [isEn]);

  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title={isEn ? "Notice Board" : "Bacheca e Avvisi"}
        subtitle={isEn ? "Stay updated on the latest communications from Pro Loco." : "Resta aggiornato sulle ultime comunicazioni della Pro Loco."}
        img="/images/pietrapertosaBacheca.png" 
        bgPosition="center 30%"
      />
      
      <PageIntro 
        title={isEn ? "Latest News" : "Ultime Notizie"}
        text={isEn ? "All official communications, calls, and important notices for residents and visitors of Pietrapertosa." : "Tutte le comunicazioni ufficiali, i bandi e gli avvisi importanti per i residenti e i visitatori di Pietrapertosa."}
      />

      <section className="ed-sec">
        <div className="ed-wrap" style={{ maxWidth: '900px' }}>
          {loading ? (
            <div style={{ textAlign: 'center', padding: '100px', color: 'var(--stone)' }}>
              {isEn ? "Loading news..." : "Caricamento notizie in corso..."}
            </div>
          ) : notizie.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '100px', color: 'var(--stone)' }}>
              {isEn ? "No news published at the moment." : "Nessuna notizia pubblicata al momento."}
            </div>
          ) : (
            <div className="ed-feed">
              {notizie.map(notizia => {
                const galleryList = typeof notizia.gallery === 'string' ? safeJsonParse(notizia.gallery) : (notizia.gallery || []);
                return (
                  <article 
                    key={notizia.id} 
                    id={`avviso-${notizia.id}`}
                    style={{ 
                      background: 'var(--ink)', 
                      padding: '50px', 
                      borderRadius: '24px', 
                      marginBottom: '40px',
                      border: '1px solid rgba(255,255,255,0.05)',
                      boxShadow: '0 20px 50px rgba(0,0,0,0.3)',
                      position: 'relative',
                      overflow: 'hidden'
                    }}
                  >
                    {notizia.cover_image_url && (
                      <div style={{ marginBottom: '30px', borderRadius: '12px', overflow: 'hidden' }}>
                        {/* eslint-disable-next-line @next/next/no-img-element */}
                        <img src={notizia.cover_image_url} alt={notizia.title} style={{ width: '100%', maxHeight: '400px', objectFit: 'cover' }} />
                      </div>
                    )}

                    <div style={{ display: 'flex', alignItems: 'baseline', gap: '20px', marginBottom: '20px', flexWrap: 'wrap' }}>
                      <span style={{ color: 'var(--gold)', fontFamily: '"Cormorant Garamond", serif', fontSize: '1.4rem' }}>
                        {new Date(notizia.created_at).toLocaleDateString('it-IT')}
                      </span>
                    </div>
                    
                    <h2 style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: 'clamp(2rem, 3vw, 2.5rem)', color: 'var(--paper)', marginBottom: '30px', lineHeight: 1.2 }}>
                      {notizia.title}
                    </h2>
                    
                    <div 
                      style={{ color: 'rgba(244,239,229,0.85)', fontSize: '1.15rem', lineHeight: 1.8 }}
                      className="ql-editor-content"
                      dangerouslySetInnerHTML={{ __html: notizia.content || notizia.excerpt }} 
                    />

                    {/* Galleria Extra */}
                    {galleryList.length > 0 && (
                      <div style={{ marginTop: '40px', paddingTop: '40px', borderTop: '1px solid rgba(255,255,255,0.1)' }}>
                        <h4 style={{ color: 'var(--paper)', marginBottom: '20px' }}>Galleria Multimediale</h4>
                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))', gap: '16px' }}>
                          {galleryList.map((url, idx) => (
                            <div key={idx} style={{ borderRadius: '12px', overflow: 'hidden', aspectRatio: '4/3', border: '1px solid rgba(255,255,255,0.05)' }}>
                              {url.includes('.mp4') || url.includes('.mov') || url.includes('.webm') ? (
                                <video src={url} controls style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                              ) : (
                                // eslint-disable-next-line @next/next/no-img-element
                                <img src={url} alt={`Media ${idx}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                              )}
                            </div>
                          ))}
                        </div>
                      </div>
                    )}
                  </article>
                );
              })}
            </div>
          )}
        </div>
      </section>

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
