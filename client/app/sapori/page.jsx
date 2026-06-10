import { safeJsonParse } from '@/utils/safeJson';
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import ExperienceCard from '@/components/ExperienceCard';
import PageCTA from '@/components/PageCTA';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { Utensils } from 'lucide-react';

import Lightbox from '@/components/Lightbox';

async function getPageData(lang) {
  const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
  
  try {
    const qs = lang === 'en' ? '?lang=en' : '';
    const qAnd = lang === 'en' ? '&lang=en' : '';
    const [pageRes, saporiRes] = await Promise.all([
      fetch(`${apiUrl}/pages/sapori${qs}`, { cache: 'no-store' }),
      fetch(`${apiUrl}/directory?category=sapori_piatti${qAnd}`, { cache: 'no-store' })
    ]);

    const pageData = pageRes.ok ? await pageRes.json() : null;
    const saporiData = saporiRes.ok ? await saporiRes.json() : { items: [] };

    // Adattamento dei dati per il componente ExperienceCard
    const mappedSapori = saporiData.items.map(s => {
      const gallery = typeof s.gallery === 'string' ? safeJsonParse(s.gallery) : (s.gallery || []);
      return {
        id: s.id,
        title: s.title,
        desc: s.description,
        img: gallery.length > 0 ? gallery[0] : '/images/placeholder.jpg',
        source: s.contact_info || '',
        status: 'verified'
      };
    });

    return {
      page: pageData ? pageData.page : null,
      sapori: mappedSapori
    };
  } catch (error) {
    console.error("Errore nel recupero dati Sapori:", error);
    return { page: null, sapori: [] };
  }
}

export default async function Sapori({ searchParams }) {
  const sp = await searchParams;
  const lang = sp?.lang || 'it';
  const { page, sapori } = await getPageData(lang);

  const isEn = lang === 'en';

  if (!page) {
    return <div className="sapori-maintenance">{isEn ? "Page under maintenance..." : "Pagina in manutenzione..."}</div>;
  }

  return (
    <StoreProvider>
      <Header />
      <SectionHero title={page.hero_title} subtitle={page.hero_subtitle} img={page.hero_image_url} />
      
      <PageIntro title={page.intro_title} text={page.intro_text} />

      <section className="wrap sapori-section">
        <div className="sapori-list">
          {sapori.map((sapore, i) => (
            <ExperienceCard key={sapore.id} esperienza={sapore} reverse={i % 2 !== 0} />
          ))}
        </div>
      </section>

      <PageCTA 
        subtitle={isEn ? "Tradition at the table" : "Tradizione a tavola"} 
        title={isEn ? "Come and taste" : "Vieni ad assaggiare"} 
        text={isEn ? "Discover local restaurants and farms in the area." : "Scopri i ristoranti locali e le aziende agricole del territorio."} 
        btnText={isEn ? "Contact us" : "Contattaci"} 
        btnLink="/contatti" 
        icon={Utensils} 
      />

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
