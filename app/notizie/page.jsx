"use client";
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { homeData } from '@/data/home';

export default function Notizie() {
  const notizieVerificate = homeData.notizie.items.filter(not => not.status === 'verified');

  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title="Bacheca e Avvisi" 
        subtitle="Resta aggiornato sulle ultime comunicazioni della Pro Loco." 
        img="/images/sapori_hero.png" 
      />
      
      <PageIntro 
        title="Ultime Notizie" 
        text="Tutte le comunicazioni ufficiali, i bandi e gli avvisi importanti per i residenti e i visitatori di Pietrapertosa." 
      />

      <section className="ed-sec">
        <div className="ed-wrap" style={{ maxWidth: '900px' }}>
          <div className="ed-feed">
            {notizieVerificate.map(notizia => (
              <article 
                key={notizia.id} 
                id={`avviso-${notizia.id}`}
                style={{ 
                  background: 'var(--ink)', 
                  padding: '50px', 
                  borderRadius: '24px', 
                  marginBottom: '40px',
                  border: '1px solid rgba(255,255,255,0.05)',
                  boxShadow: '0 20px 50px rgba(0,0,0,0.3)'
                }}
              >
                <div style={{ display: 'flex', alignItems: 'baseline', gap: '20px', marginBottom: '20px', flexWrap: 'wrap' }}>
                  <span style={{ color: 'var(--gold)', fontFamily: '"Playfair Display", serif', fontSize: '1.4rem' }}>
                    {notizia.data}
                  </span>
                  <span style={{ color: 'var(--stone)', fontSize: '0.85rem', textTransform: 'uppercase', letterSpacing: '2px', border: '1px solid var(--stone)', padding: '4px 12px', borderRadius: '100px' }}>
                    {notizia.tipo}
                  </span>
                </div>
                
                <h2 style={{ fontFamily: '"Playfair Display", serif', fontSize: 'clamp(2rem, 3vw, 2.5rem)', color: 'var(--paper)', marginBottom: '30px', lineHeight: 1.2 }}>
                  {notizia.titolo}
                </h2>
                
                <div style={{ color: 'rgba(244,239,229,0.85)', fontSize: '1.15rem', lineHeight: 1.8 }}>
                  {notizia.testo.split('\n').map((paragraph, idx) => (
                    <p key={idx} style={{ marginBottom: '16px' }}>{paragraph}</p>
                  ))}
                </div>
              </article>
            ))}
          </div>
        </div>
      </section>

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
