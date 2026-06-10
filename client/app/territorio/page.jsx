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
    return <div className="terr-maintenance">{isEn ? "Page under maintenance..." : "Pagina in manutenzione..."}</div>;
  }

  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title={page.hero_title} 
        subtitle={page.hero_subtitle} 
        img="/images/pietrapertosaTerritorio.png"
        bgPosition="center 30%"
      />
      
      <PageIntro 
        title={page.intro_title} 
        text={page.intro_text} 
      />

      {/* Sezione Aziende Agricole */}
      {aziende.length > 0 && (
        <section className="wrap terr-section-1">
          <h2 className="terr-title">
            {isEn ? "Farms" : "Aziende Agricole"}
          </h2>
          <div className="terr-list">
            {aziende.map((az, index) => {
              const gallery = typeof az.gallery === 'string' ? safeJsonParse(az.gallery) : (az.gallery || []);
              return (
                <div key={az.id} className={`ed-split terr-item ${index % 2 !== 0 ? 'terr-item-rtl' : 'terr-item-ltr'}`}>
                  <div className="terr-text-reset">
                    <span className="lbl-mut terr-item-subtitle">{az.subtitle}</span>
                    <h3 className="terr-item-title">
                      {az.title}
                    </h3>
                    <p className="terr-item-desc">
                      {az.description}
                    </p>
                    {az.contact_info && <div className="terr-item-contact">{isEn ? "Contact:" : "Contatto:"} {az.contact_info}</div>}
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
        <section className="wrap terr-section-2">
          <h2 className="terr-title">
            {isEn ? "Food Truck and Street Food" : "Food Truck e Street Food"}
          </h2>
          <div className="terr-list">
            {foodtruck.map((ft, index) => {
              const gallery = typeof ft.gallery === 'string' ? safeJsonParse(ft.gallery) : (ft.gallery || []);
              return (
                <div key={ft.id} className={`ed-split terr-item ${index % 2 === 0 ? 'terr-item-rtl' : 'terr-item-ltr'}`}>
                  <div className="terr-text-reset">
                    <span className="lbl-mut terr-item-subtitle">{ft.subtitle}</span>
                    <h3 className="terr-item-title">
                      {ft.title}
                    </h3>
                    <p className="terr-item-desc">
                      {ft.description}
                    </p>
                    {ft.contact_info && <div className="terr-item-contact">{isEn ? "Contact:" : "Contatto:"} {ft.contact_info}</div>}
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
        <section className="wrap terr-section-3">
          <h2 className="terr-title">
            {isEn ? "Artisans" : "Artigiani"}
          </h2>
          <div className="terr-list">
            {artigiani.map((art, index) => {
              const gallery = typeof art.gallery === 'string' ? safeJsonParse(art.gallery) : (art.gallery || []);
              return (
                <div key={art.id} className={`ed-split terr-item ${index % 2 !== 0 ? 'terr-item-rtl' : 'terr-item-ltr'}`}>
                  <div className="terr-text-reset">
                    <span className="lbl-mut terr-item-subtitle">{art.subtitle}</span>
                    <h3 className="terr-item-title">
                      {art.title}
                    </h3>
                    <p className="terr-item-desc">
                      {art.description}
                    </p>
                    {art.contact_info && <div className="terr-item-contact">{isEn ? "Contact:" : "Contatto:"} {art.contact_info}</div>}
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
