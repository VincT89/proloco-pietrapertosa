"use client";
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { comunitaData } from '@/data/comunita';

export default function Comunita() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title={comunitaData.hero.title} 
        subtitle={comunitaData.hero.subtitle} 
        img={comunitaData.hero.img} 
      />
      
      <PageIntro 
        title={comunitaData.intro.title} 
        text={comunitaData.intro.text} 
      />

      {/* Sezione Le Nostre Realtà - Sticky Scroll Layout */}
      <section style={{ position: 'relative', background: 'var(--ink)' }}>
        <style dangerouslySetInnerHTML={{__html: `
          .sticky-split { display: flex; flex-direction: row; border-bottom: 1px solid rgba(255,255,255,0.05); }
          .sticky-text { 
            width: 45%; 
            position: sticky; 
            top: 0; 
            height: 100vh; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            padding: 0 clamp(40px, 8vw, 120px);
            background: var(--ink);
            z-index: 10;
          }
          .sticky-imgs { 
            width: 55%; 
            display: flex; 
            flex-direction: column; 
            padding: 10vh 40px; /* Spazio sopra e sotto per far scorrere bene */
            gap: 60px; /* Stacca le immagini tra loro */
          }
          .sticky-img-box { 
            width: 100%; 
            height: clamp(400px, 60vh, 700px); /* Non più 100vh, ma molto più piccole */
            position: relative; 
            border-radius: 24px; /* Bordi eleganti */
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.03);
          }
          
          @media (max-width: 1000px) {
            .sticky-split { flex-direction: column; border-bottom: none; }
            .sticky-text { 
              width: 100%; 
              height: auto; 
              position: relative; 
              padding: 80px clamp(20px, 5vw, 60px) !important; 
              border-bottom: 1px solid rgba(255,255,255,0.05);
            }
            .sticky-imgs { width: 100%; padding: 40px 20px; gap: 30px; }
            .sticky-img-box { height: 50vh; }
          }
        `}} />

        {comunitaData.realta.map((asso) => (
          <div key={asso.id} className="sticky-split">
            
            {/* Left Side: Testo Bloccato (Sticky) */}
            <div className="sticky-text">
              <span style={{ color: 'var(--gold)', textTransform: 'uppercase', letterSpacing: '3px', fontSize: '0.85rem', fontWeight: 600, display: 'inline-block', marginBottom: '24px' }}>
                {asso.categoria}
              </span>
              <h3 style={{ fontFamily: '"Playfair Display", serif', fontSize: 'clamp(2.5rem, 4vw, 4.5rem)', color: 'var(--paper)', margin: '0 0 30px', lineHeight: 1.1 }}>
                {asso.nome}
              </h3>
              <p style={{ color: 'rgba(244,239,229,0.85)', fontSize: '1.15rem', lineHeight: 1.8, margin: 0 }}>
                {asso.descrizione}
              </p>
            </div>

            {/* Right Side: Torre delle Immagini a scorrimento */}
            <div className="sticky-imgs">
              {asso.immagini.map((img, i) => (
                <div key={i} className="sticky-img-box">
                  <img src={img} alt={`${asso.nome} ${i + 1}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                  {/* Gradiente sfumato sui bordi delle foto per fonderle col buio */}
                  <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to right, var(--ink) 0%, transparent 15%, transparent 85%, var(--ink) 100%)', pointerEvents: 'none' }} />
                </div>
              ))}
            </div>

          </div>
        ))}
      </section>

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
