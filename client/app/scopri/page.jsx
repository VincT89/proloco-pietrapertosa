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

  const pageData = {
    it: {
      hero: { title: "Scopri e Vivi", subtitle: "Un viaggio tra le Dolomiti Lucane", img: "/images/PietrapertosaScopri.jpg" },
      luoghi: [
        { id: 1, nome: "L'Arabata", img: "/images/arabata01.jpg" },
        { id: 2, nome: "Il Castello Normanno-Svevo", img: "/images/castello.jpg" },
        { id: 3, nome: "Il Sentiero delle Sette Pietre", img: "/images/percorsi.jpg" },
        { id: 4, nome: "Il Volo dell'Angelo", img: "/images/voloAngelo.jpg" }
      ]
    },
    en: {
      hero: { title: "Discover and Live", subtitle: "A journey through the Lucanian Dolomites", img: "/images/PietrapertosaScopri.jpg" },
      luoghi: [
        { id: 1, nome: "The Arabata", img: "/images/arabata01.jpg" },
        { id: 2, nome: "The Norman-Swabian Castle", img: "/images/castello.jpg" },
        { id: 3, nome: "The Path of the Seven Stones", img: "/images/percorsi.jpg" },
        { id: 4, nome: "Flight of the Angel", img: "/images/voloAngelo.jpg" }
      ]
    }
  };

  const currentData = isEn ? pageData.en : pageData.it;
  const luoghi = currentData.luoghi;
  const servizi = isEn ? vivereData.en.servizi : vivereData.it.servizi;

  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title={isEn ? "Discover and Live" : "Scopri e Vivi"} 
        subtitle={isEn ? "A journey through the Lucanian Dolomites" : "Un viaggio tra le Dolomiti Lucane"} 
        img={currentData.hero.img} 
      />
      
      <PageIntro 
        title={isEn ? "Must-see places" : "I luoghi imperdibili"} 
        text={isEn ? "Explore the historical and naturalistic wonders of our village. And when it's time to rest or eat, trust our welcoming facilities." : "Esplora le meraviglie storiche e naturalistiche del nostro paese. E quando è l'ora di riposare o mangiare, affidati alle nostre strutture accoglienti."} 
      />

      {/* Sezione Luoghi - Senza pulsanti ripetitivi */}
      <section className="wrap scopri-section-1">
        <div className="scopri-grid-1">
          {luoghi.map(luogo => (
            <div key={luogo.id} className="scopri-card">
              <div className="scopri-img-wrap">
                <img src={luogo.img} alt={luogo.nome} className="scopri-img" />
              </div>
              <div className="scopri-card-overlay">
                <h3 className="scopri-card-title">{luogo.nome}</h3>
              </div>
            </div>
          ))}
        </div>

        <div className="scopri-cta-wrap">
          <p className="scopri-cta-text">{isEn ? "Want to explore the itineraries step by step?" : "Vuoi esplorare gli itinerari passo dopo passo?"}</p>
          <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" className="ed-btn scopri-btn-solid">
            {isEn ? "Visit Borgo Racconta portal" : "Visita il portale Borgo Racconta"}
          </a>
        </div>
      </section>

      {/* Sezione Ospitalità e Ristorazione - Redirect a Borgo Racconta */}
      <section className="wrap scopri-section-2">
        <div className="scopri-box-outline">
          <h2 className="scopri-box-title">
            {isEn ? "Where to Eat and Sleep" : "Dove Mangiare e Dormire"}
          </h2>
          <p className="scopri-box-desc">
            {isEn ? "Looking for a cozy place to rest or want to taste typical dishes in the village restaurants? The official and constantly updated list of all accommodation and dining facilities in Pietrapertosa is managed directly on the tourist portal." : "Cerchi un posto accogliente dove riposare o vuoi gustare i piatti tipici nei ristoranti del paese? L'elenco ufficiale e costantemente aggiornato di tutte le strutture ricettive e della ristorazione di Pietrapertosa è curato direttamente sul portale turistico."}
          </p>
          <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" className="ed-btn scopri-btn-outline">
            {isEn ? "Check the list on Borgo Racconta" : "Consulta l'elenco su Borgo Racconta"}
          </a>
        </div>
      </section>

      {/* Sezione Servizi */}
      <section className="wrap scopri-section-3">
        <h2 className="scopri-info-title">
          {isEn ? "Useful Numbers and Services" : "Numeri e Servizi Utili"}
        </h2>
        <div className="scopri-grid-2">
          {servizi.map(item => (
            <div key={item.id} className="scopri-info-card">
              <h3 className="scopri-info-card-title">{item.nome}</h3>
              <p className="scopri-info-card-text">{item.info}</p>
              <div className="scopri-info-card-contact">{item.contatto}</div>
            </div>
          ))}
        </div>
      </section>

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
