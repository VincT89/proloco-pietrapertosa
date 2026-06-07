import React from 'react';
import Header from '@/components/Header';
import Hero from '@/components/Hero';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { homeData } from '@/data/home';
import Link from 'next/link';

export default function Home() {
  // Filtriamo solo i dati pronti per la pubblicazione
  const eventiVerificati = homeData.eventi.items.filter(ev => ev.status === 'verified');
  const comunitaVerificata = homeData.comunita.items.filter(com => com.status === 'verified');
  const notizieVerificate = homeData.notizie.items.filter(not => not.status === 'verified');
  const scopriVerificati = homeData.scopri.items.filter(sc => sc.status === 'verified');

  return (
    <StoreProvider>
      <Header />
      <Hero />

      {/* Prossimi Eventi */}
      {eventiVerificati.length > 0 && (
        <section className="ed-sec">
          <div className="ed-wrap">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '60px' }}>
              <div>
                <span className="ed-subtitle">Calendario</span>
                <h2 className="ed-title" style={{ marginBottom: 0 }}>{homeData.eventi.titolo}</h2>
              </div>
              <Link href="/eventi" style={{ color: 'var(--ink)', textDecoration: 'none', fontSize: '0.9rem', textTransform: 'uppercase', letterSpacing: '1px', borderBottom: '1px solid var(--ink)', paddingBottom: '4px' }}>
                Vedi tutti
              </Link>
            </div>
            
            <div className="ed-grid">
              {eventiVerificati.map(ev => (
                <div key={ev.id} className="ed-card">
                  <div className="ed-img-box tall">
                    <img src={ev.img} alt={ev.nome} />
                  </div>
                  <div className="ed-glass-cap">
                    <span>{ev.data}</span>
                    <h3>{ev.nome}</h3>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Ultime Notizie (Sostituisce Comunità) */}
      {notizieVerificate.length > 0 && (
        <section className="ed-sec alt">
          <div className="ed-wrap">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '60px' }}>
              <div>
                <span className="ed-subtitle">Aggiornamenti in primo piano</span>
                <h2 className="ed-title" style={{ marginBottom: 0 }}>{homeData.notizie.titolo}</h2>
              </div>
            </div>
            
            <div className="ed-list" style={{ borderTop: '1px solid var(--hair)' }}>
              {notizieVerificate.map(notizia => (
                <Link key={notizia.id} href={`/notizie#avviso-${notizia.id}`} style={{ textDecoration: 'none' }}>
                  <div className="ed-list-item" style={{ padding: '20px 0', borderBottom: '1px solid var(--hair)', display: 'flex', justifyContent: 'space-between', alignItems: 'center', transition: 'background 0.3s', cursor: 'pointer' }}>
                    <div style={{ display: 'flex', alignItems: 'baseline', gap: '30px', flexWrap: 'wrap' }}>
                      <div style={{ color: 'var(--gold)', fontFamily: '"Playfair Display", serif', fontSize: '1.1rem', minWidth: '100px' }}>{notizia.data}</div>
                      <div>
                        <h4 style={{ fontSize: '1.3rem', fontFamily: '"Playfair Display", serif', color: 'var(--paper)', marginBottom: '4px', fontWeight: 400 }}>{notizia.titolo}</h4>
                        <p style={{ color: 'var(--stone)', fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', margin: 0 }}>{notizia.tipo}</p>
                      </div>
                    </div>
                    <span style={{ color: 'var(--gold)', fontSize: '1.2rem', marginLeft: '20px' }}>→</span>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Scopri Pietrapertosa */}
      {scopriVerificati.length > 0 && (
        <section className="ed-sec alt">
          <div className="ed-wrap">
            <div className="ed-split" style={{ alignItems: 'center' }}>
              <div style={{ display: 'flex', flexDirection: 'column', justifyContent: 'center' }}>
                <span className="ed-subtitle">Esplora il territorio</span>
                <h2 className="ed-title" style={{ marginBottom: '30px', fontSize: 'clamp(2.5rem, 5vw, 4.5rem)' }}>{homeData.scopri.titolo}</h2>
                <p style={{ color: 'var(--stone)', fontSize: '1.1rem', marginBottom: '40px', maxWidth: '45ch', lineHeight: 1.6 }}>
                  Dall'Arabatana al Castello Normanno-Svevo, fino all'emozionante Volo dell'Angelo. Scopri i luoghi che rendono unico il nostro borgo.
                </p>
                
                <ul style={{ listStyle: 'none', padding: 0, margin: '0 0 40px 0', display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  {scopriVerificati.map(scopri => (
                    <li key={scopri.id} style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                      <span style={{ color: 'var(--gold)' }}>✦</span>
                      <span style={{ color: 'var(--paper)', fontSize: '1.3rem', fontFamily: '"Newsreader", serif' }}>{scopri.nome}</span>
                    </li>
                  ))}
                </ul>

                <div>
                  <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" className="ed-btn" style={{ textDecoration: 'none', background: 'var(--gold)', color: 'var(--ink)' }}>
                    Vai a Borgo Racconta
                  </a>
                </div>
              </div>

              <div className="borgo-imgs">
                {scopriVerificati[0] && (
                  <div className="bi1">
                    <img src={scopriVerificati[0].img} alt={scopriVerificati[0].nome} />
                  </div>
                )}
                {scopriVerificati[1] && (
                  <div className="bi2">
                    <img src={scopriVerificati[1].img} alt={scopriVerificati[1].nome} />
                  </div>
                )}
                {scopriVerificati[2] && (
                  <div className="bi3">
                    <img src={scopriVerificati[2].img} alt={scopriVerificati[2].nome} />
                  </div>
                )}
              </div>
            </div>
          </div>
        </section>
      )}

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
