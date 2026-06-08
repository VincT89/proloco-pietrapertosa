import { safeJsonParse } from '@/utils/safeJson';
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';

import InteractiveCollage from '@/components/InteractiveCollage';

async function getPageData(lang) {
  const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
  
  try {
    const qs = lang === 'en' ? '?lang=en' : '';
    const qAnd = lang === 'en' ? '&lang=en' : '';
    const [pageRes, aziendeRes, foodtruckRes, artigianiRes] = await Promise.all([
      fetch(`${apiUrl}/pages/territorio${qs}`, { cache: 'no-store' }),
      fetch(`${apiUrl}/directory?category=territorio_aziende${qAnd}`, { cache: 'no-store' }),
      fetch(`${apiUrl}/directory?category=territorio_foodtruck${qAnd}`, { cache: 'no-store' }),
      fetch(`${apiUrl}/directory?category=territorio_artigiani${qAnd}`, { cache: 'no-store' })
    ]);

    const pageData = pageRes.ok ? await pageRes.json() : null;
    const aziendeData = aziendeRes.ok ? await aziendeRes.json() : { items: [] };
    const foodtruckData = foodtruckRes.ok ? await foodtruckRes.json() : { items: [] };
    const artigianiData = artigianiRes.ok ? await artigianiRes.json() : { items: [] };

    return {
      page: pageData ? pageData.page : null,
      aziende: aziendeData.items,
      foodtruck: foodtruckData.items,
      artigiani: artigianiData.items
    };
  } catch (error) {
    console.error("Errore nel recupero dati Territorio:", error);
    return { page: null, aziende: [], foodtruck: [], artigiani: [] };
  }
}

export default async function Territorio({ searchParams }) {
  const sp = await searchParams;
  const lang = sp?.lang || 'it';
  const { page, aziende, foodtruck, artigiani } = await getPageData(lang);

  const isEn = lang === 'en';

  if (!page) {
    return <div style={{ padding: '100px', textAlign: 'center', color: 'white' }}>{isEn ? "Page under maintenance..." : "Pagina in manutenzione..."}</div>;
  }

  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title={page.hero_title} 
        subtitle={page.hero_subtitle} 
        img={page.hero_image_url} 
      />
      
      <PageIntro 
        title={page.intro_title} 
        text={page.intro_text} 
      />

      {/* Sezione Aziende Agricole */}
      {aziende.length > 0 && (
        <section className="wrap" style={{ paddingBottom: '80px', paddingTop: '40px' }}>
          <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-cormorant), serif', marginBottom: '60px', color: 'var(--paper)' }}>
            {isEn ? "Farms" : "Aziende Agricole"}
          </h2>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '100px' }}>
            {aziende.map((az, index) => {
              const gallery = typeof az.gallery === 'string' ? safeJsonParse(az.gallery) : (az.gallery || []);
              return (
                <div key={az.id} className="ed-split" style={{ alignItems: 'center', direction: index % 2 !== 0 ? 'rtl' : 'ltr' }}>
                  <div style={{ direction: 'ltr' }}>
                    <span className="lbl-mut" style={{ color: 'var(--gold)', letterSpacing: '0.2em' }}>{az.subtitle}</span>
                    <h3 style={{ fontSize: '2.2rem', fontFamily: '"Cormorant Garamond", serif', margin: '16px 0', color: 'var(--paper)', lineHeight: 1.2 }}>
                      {az.title}
                    </h3>
                    <p style={{ color: 'var(--stone)', fontSize: '1.1rem', lineHeight: '1.7', maxWidth: '45ch', marginBottom: '20px', whiteSpace: 'pre-wrap' }}>
                      {az.description}
                    </p>
                    {az.contact_info && <div style={{ fontSize: '0.85rem', color: 'var(--stone)' }}>{isEn ? "Contact:" : "Contatto:"} {az.contact_info}</div>}
                  </div>
                  <InteractiveCollage images={gallery} altText={az.title} />
                </div>
              );
            })}
          </div>
        </section>
      )}

      {/* Sezione Food Truck */}
      {foodtruck.length > 0 && (
        <section className="wrap" style={{ paddingBottom: '80px' }}>
          <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-cormorant), serif', marginBottom: '60px', color: 'var(--paper)' }}>
            {isEn ? "Food Truck and Street Food" : "Food Truck e Street Food"}
          </h2>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '100px' }}>
            {foodtruck.map((ft, index) => {
              const gallery = typeof ft.gallery === 'string' ? safeJsonParse(ft.gallery) : (ft.gallery || []);
              return (
                <div key={ft.id} className="ed-split" style={{ alignItems: 'center', direction: index % 2 === 0 ? 'rtl' : 'ltr' }}>
                  <div style={{ direction: 'ltr' }}>
                    <span className="lbl-mut" style={{ color: 'var(--gold)', letterSpacing: '0.2em' }}>{ft.subtitle}</span>
                    <h3 style={{ fontSize: '2.2rem', fontFamily: '"Cormorant Garamond", serif', margin: '16px 0', color: 'var(--paper)', lineHeight: 1.2 }}>
                      {ft.title}
                    </h3>
                    <p style={{ color: 'var(--stone)', fontSize: '1.1rem', lineHeight: '1.7', maxWidth: '45ch', marginBottom: '20px', whiteSpace: 'pre-wrap' }}>
                      {ft.description}
                    </p>
                    {ft.contact_info && <div style={{ fontSize: '0.85rem', color: 'var(--stone)' }}>{isEn ? "Contact:" : "Contatto:"} {ft.contact_info}</div>}
                  </div>
                  <InteractiveCollage images={gallery} altText={ft.title} />
                </div>
              );
            })}
          </div>
        </section>
      )}

      {/* Sezione Artigiani */}
      {artigiani.length > 0 && (
        <section className="wrap" style={{ paddingBottom: '100px' }}>
          <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-cormorant), serif', marginBottom: '60px', color: 'var(--paper)' }}>
            {isEn ? "Artisans" : "Artigiani"}
          </h2>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '100px' }}>
            {artigiani.map((art, index) => {
              const gallery = typeof art.gallery === 'string' ? safeJsonParse(art.gallery) : (art.gallery || []);
              return (
                <div key={art.id} className="ed-split" style={{ alignItems: 'center', direction: index % 2 !== 0 ? 'rtl' : 'ltr' }}>
                  <div style={{ direction: 'ltr' }}>
                    <span className="lbl-mut" style={{ color: 'var(--gold)', letterSpacing: '0.2em' }}>{art.subtitle}</span>
                    <h3 style={{ fontSize: '2.2rem', fontFamily: '"Cormorant Garamond", serif', margin: '16px 0', color: 'var(--paper)', lineHeight: 1.2 }}>
                      {art.title}
                    </h3>
                    <p style={{ color: 'var(--stone)', fontSize: '1.1rem', lineHeight: '1.7', maxWidth: '45ch', marginBottom: '20px', whiteSpace: 'pre-wrap' }}>
                      {art.description}
                    </p>
                    {art.contact_info && <div style={{ fontSize: '0.85rem', color: 'var(--stone)' }}>{isEn ? "Contact:" : "Contatto:"} {art.contact_info}</div>}
                  </div>
                  <InteractiveCollage images={gallery} altText={art.title} />
                </div>
              );
            })}
          </div>
        </section>
      )}

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
