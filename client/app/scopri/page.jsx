import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';

import { vivereData } from '@/data/vivere';

export default async function Scopri({ searchParams }) {
  const sp = await searchParams;
  const isEn = sp?.lang === 'en';

  const luoghiIt = [
    { id: 1, nome: "L'Arabata", img: "/images/arabata01.jpg" },
    { id: 2, nome: "Il Castello Normanno-Svevo", img: "/images/castello.jpg" },
    { id: 3, nome: "Il Sentiero delle Sette Pietre", img: "/images/percorsi.jpg" },
    { id: 4, nome: "Il Volo dell'Angelo", img: "/images/voloAngelo.jpg" }
  ];

  const luoghiEn = [
    { id: 1, nome: "The Arabata", img: "/images/arabata01.jpg" },
    { id: 2, nome: "The Norman-Swabian Castle", img: "/images/castello.jpg" },
    { id: 3, nome: "The Path of the Seven Stones", img: "/images/percorsi.jpg" },
    { id: 4, nome: "Flight of the Angel", img: "/images/voloAngelo.jpg" }
  ];

  const luoghi = isEn ? luoghiEn : luoghiIt;
  const servizi = isEn ? vivereData.en.servizi : vivereData.it.servizi;

  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title={isEn ? "Discover and Live" : "Scopri e Vivi"} 
        subtitle={isEn ? "A journey through the Lucanian Dolomites" : "Un viaggio tra le Dolomiti Lucane"} 
        img="/images/immaginePaese.jpg" 
      />
      
      <PageIntro 
        title={isEn ? "Must-see places" : "I luoghi imperdibili"} 
        text={isEn ? "Explore the historical and naturalistic wonders of our village. And when it's time to rest or eat, trust our welcoming facilities." : "Esplora le meraviglie storiche e naturalistiche del nostro paese. E quando è l'ora di riposare o mangiare, affidati alle nostre strutture accoglienti."} 
      />

      {/* Sezione Luoghi - Senza pulsanti ripetitivi */}
      <section className="wrap" style={{ paddingBottom: '60px', paddingTop: '40px' }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '20px' }}>
          {luoghi.map(luogo => (
            <div key={luogo.id} style={{ position: 'relative', overflow: 'hidden', borderRadius: '4px' }}>
              <div style={{ width: '100%', height: '320px', overflow: 'hidden' }}>
                <img src={luogo.img} alt={luogo.nome} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
              </div>
              <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, background: 'linear-gradient(to top, rgba(0,0,0,0.9), transparent)', padding: '50px 24px 24px', pointerEvents: 'none' }}>
                <h3 style={{ fontSize: '1.6rem', color: 'var(--paper)', margin: 0, fontFamily: 'var(--font-cormorant), serif', textShadow: '0 2px 8px rgba(0,0,0,0.5)' }}>{luogo.nome}</h3>
              </div>
            </div>
          ))}
        </div>

        <div style={{ textAlign: 'center', marginTop: '60px' }}>
          <p style={{ color: 'var(--stone)', marginBottom: '24px', fontSize: '1.1rem' }}>{isEn ? "Want to explore the itineraries step by step?" : "Vuoi esplorare gli itinerari passo dopo passo?"}</p>
          <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" className="ed-btn" style={{ textDecoration: 'none', background: 'var(--gold)', color: 'var(--ink)', padding: '16px 36px', fontSize: '1rem', display: 'inline-block' }}>
            {isEn ? "Visit Borgo Racconta portal" : "Visita il portale Borgo Racconta"}
          </a>
        </div>
      </section>

      {/* Sezione Ospitalità e Ristorazione - Redirect a Borgo Racconta */}
      <section className="wrap" style={{ paddingBottom: '80px', paddingTop: '60px', borderTop: '1px solid var(--hair)' }}>
        <div style={{ background: 'var(--ink-2)', border: '1px solid rgba(217,170,99,0.3)', padding: 'clamp(40px, 6vw, 60px)', textAlign: 'center', borderRadius: '4px' }}>
          <h2 style={{ fontSize: '2.4rem', fontFamily: 'var(--font-cormorant), serif', marginBottom: '20px', color: 'var(--paper)' }}>
            {isEn ? "Where to Eat and Sleep" : "Dove Mangiare e Dormire"}
          </h2>
          <p style={{ color: 'var(--stone)', fontSize: '1.1rem', maxWidth: '65ch', margin: '0 auto 36px', lineHeight: 1.7 }}>
            {isEn ? "Looking for a cozy place to rest or want to taste typical dishes in the village restaurants? The official and constantly updated list of all accommodation and dining facilities in Pietrapertosa is managed directly on the tourist portal." : "Cerchi un posto accogliente dove riposare o vuoi gustare i piatti tipici nei ristoranti del paese? L'elenco ufficiale e costantemente aggiornato di tutte le strutture ricettive e della ristorazione di Pietrapertosa è curato direttamente sul portale turistico."}
          </p>
          <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" className="ed-btn" style={{ textDecoration: 'none', background: 'transparent', border: '1px solid var(--gold)', color: 'var(--gold)', padding: '16px 36px', fontSize: '1rem', display: 'inline-block', transition: 'all 0.3s' }}>
            {isEn ? "Check the list on Borgo Racconta" : "Consulta l'elenco su Borgo Racconta"}
          </a>
        </div>
      </section>

      {/* Sezione Servizi */}
      <section className="wrap" style={{ paddingBottom: '100px' }}>
        <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-cormorant), serif', marginBottom: '40px', color: 'var(--paper)' }}>
          {isEn ? "Useful Numbers and Services" : "Numeri e Servizi Utili"}
        </h2>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
          {servizi.map(item => (
            <div key={item.id} style={{ borderLeft: '3px solid var(--gold)', padding: '16px', background: 'var(--ink)' }}>
              <h3 style={{ fontSize: '1.2rem', fontFamily: 'var(--font-cormorant), serif', marginBottom: '6px', color: 'var(--paper)' }}>{item.nome}</h3>
              <p style={{ color: 'var(--stone)', fontSize: '0.9rem', marginBottom: '8px' }}>{item.info}</p>
              <div style={{ fontSize: '0.9rem', color: 'var(--gold-soft)' }}>{item.contatto}</div>
            </div>
          ))}
        </div>
      </section>

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
