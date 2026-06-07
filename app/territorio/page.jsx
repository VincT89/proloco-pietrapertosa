import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';

import { territorioData } from '@/data/territorio';
import InteractiveCollage from '@/components/InteractiveCollage';

export default function Territorio() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title="Il Territorio" 
        subtitle="Mani che creano e mani che coltivano" 
        img="/images/sapori_hero.png" 
      />
      
      <PageIntro 
        title="Artigiani e Aziende" 
        text="Dietro i sapori e i manufatti della nostra terra ci sono le storie di persone straordinarie. Scopri le aziende agricole, i food truck e i maestri artigiani di Pietrapertosa." 
      />

      {/* Sezione Aziende Agricole */}
      <section className="wrap" style={{ paddingBottom: '80px', paddingTop: '40px' }}>
        <h2 style={{ fontSize: '2.5rem', fontFamily: '"Playfair Display", serif', marginBottom: '60px', color: 'var(--paper)' }}>
          Aziende Agricole
        </h2>
        <div style={{ display: 'flex', flexDirection: 'column', gap: '100px' }}>
          {territorioData.aziendeAgricole.map((az, index) => (
            <div key={az.id} className="ed-split" style={{ alignItems: 'center', direction: index % 2 !== 0 ? 'rtl' : 'ltr' }}>
              <div style={{ direction: 'ltr' }}>
                <span className="lbl-mut" style={{ color: 'var(--gold)', letterSpacing: '0.2em' }}>{az.prodotto}</span>
                <h3 style={{ fontSize: '2.2rem', fontFamily: '"Playfair Display", serif', margin: '16px 0', color: 'var(--paper)', lineHeight: 1.2 }}>
                  {az.nome}
                </h3>
                <p style={{ color: 'var(--stone)', fontSize: '1.1rem', lineHeight: '1.7', maxWidth: '45ch', marginBottom: '20px' }}>
                  {az.descrizione}
                </p>
                <div style={{ fontSize: '0.85rem', color: 'var(--stone)' }}>Contatto: {az.contatto}</div>
              </div>

              <InteractiveCollage images={az.immagini} altText={az.nome} />
            </div>
          ))}
        </div>
      </section>

      {/* Sezione Food Truck */}
      <section className="wrap" style={{ paddingBottom: '80px' }}>
        <h2 style={{ fontSize: '2.5rem', fontFamily: '"Playfair Display", serif', marginBottom: '60px', color: 'var(--paper)' }}>
          Food Truck e Street Food
        </h2>
        <div style={{ display: 'flex', flexDirection: 'column', gap: '100px' }}>
          {territorioData.foodTruck.map((ft, index) => (
            <div key={ft.id} className="ed-split" style={{ alignItems: 'center', direction: index % 2 === 0 ? 'rtl' : 'ltr' }}>
              <div style={{ direction: 'ltr' }}>
                <span className="lbl-mut" style={{ color: 'var(--gold)', letterSpacing: '0.2em' }}>{ft.specialita}</span>
                <h3 style={{ fontSize: '2.2rem', fontFamily: '"Playfair Display", serif', margin: '16px 0', color: 'var(--paper)', lineHeight: 1.2 }}>
                  {ft.nome}
                </h3>
                <p style={{ color: 'var(--stone)', fontSize: '1.1rem', lineHeight: '1.7', maxWidth: '45ch', marginBottom: '20px' }}>
                  {ft.descrizione}
                </p>
                <div style={{ fontSize: '0.85rem', color: 'var(--stone)' }}>Contatto: {ft.contatto}</div>
              </div>

              <InteractiveCollage images={ft.immagini} altText={ft.nome} />
            </div>
          ))}
        </div>
      </section>

      {/* Sezione Artigiani */}
      <section className="wrap" style={{ paddingBottom: '100px' }}>
        <h2 style={{ fontSize: '2.5rem', fontFamily: '"Playfair Display", serif', marginBottom: '60px', color: 'var(--paper)' }}>
          Artigiani
        </h2>
        <div style={{ display: 'flex', flexDirection: 'column', gap: '100px' }}>
          {territorioData.artigiani.map((art, index) => (
            <div key={art.id} className="ed-split" style={{ alignItems: 'center', direction: index % 2 !== 0 ? 'rtl' : 'ltr' }}>
              <div style={{ direction: 'ltr' }}>
                <span className="lbl-mut" style={{ color: 'var(--gold)', letterSpacing: '0.2em' }}>{art.mestiere}</span>
                <h3 style={{ fontSize: '2.2rem', fontFamily: '"Playfair Display", serif', margin: '16px 0', color: 'var(--paper)', lineHeight: 1.2 }}>
                  {art.nome}
                </h3>
                <p style={{ color: 'var(--stone)', fontSize: '1.1rem', lineHeight: '1.7', maxWidth: '45ch', marginBottom: '20px' }}>
                  {art.descrizione}
                </p>
                <div style={{ fontSize: '0.85rem', color: 'var(--stone)' }}>Contatto: {art.contatto}</div>
              </div>

              <InteractiveCollage images={art.immagini} altText={art.nome} />
            </div>
          ))}
        </div>
      </section>

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
