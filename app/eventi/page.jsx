import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { eventiData } from '@/data/eventi';
import AutoCarousel from '@/components/AutoCarousel';

export default function Eventi() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero title={eventiData.hero.title} subtitle={eventiData.hero.subtitle} img={eventiData.hero.img} />
      
      {/* Eventi Annuali - Giant Cards Verticali */}
      <section style={{ position: 'relative', padding: 'clamp(80px, 10vw, 140px) 0', background: 'var(--ink)' }}>
        <div className="ed-wrap" style={{ textAlign: 'center', marginBottom: 'clamp(50px, 8vw, 80px)', padding: '0 clamp(20px, 4.5vw, 64px)' }}>
          <span className="ed-subtitle">Tradizioni vive</span>
          <h2 className="ed-title" style={{ margin: 0 }}>Eventi Annuali</h2>
        </div>

        <div 
          style={{ 
            display: 'flex', 
            flexDirection: 'column',
            gap: 'clamp(60px, 8vw, 120px)', 
            alignItems: 'center',
            padding: '0 clamp(20px, 4.5vw, 64px)'
          }}
        >
          {eventiData.annuali.map((ev, index) => (
            <div 
              key={ev.id} 
              style={{ 
                width: '100%',
                maxWidth: '1200px', 
                height: 'clamp(450px, 70vh, 700px)', 
                position: 'relative',
                display: 'flex',
                alignItems: 'center',
                borderRadius: '30px', 
                overflow: 'hidden',
                boxShadow: '0 25px 60px -15px rgba(0,0,0,0.7)',
                border: '1px solid rgba(255,255,255,0.05)'
              }}
            >
              {/* Sfondo Carosello */}
              <div style={{ position: 'absolute', inset: 0, zIndex: 1 }}>
                <AutoCarousel images={ev.immagini} interval={4500 + index * 500} />
              </div>
              
              {/* Gradiente Scuro per Leggibilità (da sinistra a destra) */}
              <div style={{ position: 'absolute', inset: 0, zIndex: 2, background: 'linear-gradient(to right, rgba(13,16,20,0.95) 0%, rgba(13,16,20,0.5) 60%, transparent 100%)' }} />
              
              {/* Contenuto Editoriale */}
              <div style={{ position: 'relative', zIndex: 3, width: '100%', padding: '0 clamp(30px, 6vw, 80px)' }}>
                <div style={{ maxWidth: '600px' }}>
                  <span style={{ color: 'var(--gold)', textTransform: 'uppercase', letterSpacing: '3px', fontSize: '0.85rem', fontWeight: 600, display: 'inline-block', marginBottom: '24px', borderBottom: '1px solid var(--gold)', paddingBottom: '6px' }}>
                    {ev.periodo}
                  </span>
                  <h3 style={{ fontFamily: '"Playfair Display", serif', fontSize: 'clamp(2.5rem, 5vw, 4.5rem)', color: 'var(--paper)', margin: '0 0 24px', lineHeight: 1.05 }}>
                    {ev.nome}
                  </h3>
                  <p style={{ color: 'rgba(244,239,229,0.85)', fontSize: '1.1rem', lineHeight: 1.8, margin: 0 }}>
                    {ev.descrizioneBreve}
                  </p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* Calendario e Archivio */}
      <section className="ed-sec alt">
        <div className="ed-wrap">
          <div className="ed-split">
            
            {/* Calendario Eventi Futuri */}
            <div>
              <span className="ed-subtitle">In Programma</span>
              <h2 className="ed-title" style={{ fontSize: '3rem' }}>Calendario</h2>
              <div className="ed-list">
                {eventiData.calendario.map(cal => (
                  <div key={cal.id} className="ed-list-item">
                    <div className="ed-list-meta">{cal.data}</div>
                    <div className="ed-list-main">
                      <h4>{cal.titolo}</h4>
                      <p>{cal.info}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Archivio */}
            <div>
              <span className="ed-subtitle">Memoria</span>
              <h2 className="ed-title" style={{ fontSize: '3rem' }}>Archivio</h2>
              <div className="ed-list" style={{ opacity: 0.7 }}>
                {eventiData.archivio.map(arc => (
                  <div key={arc.id} className="ed-list-item">
                    <div className="ed-list-meta" style={{ color: 'var(--stone)' }}>{arc.anno}</div>
                    <div className="ed-list-main">
                      <h4>{arc.titolo}</h4>
                      <p>{arc.info}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>

          </div>
        </div>
      </section>

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
