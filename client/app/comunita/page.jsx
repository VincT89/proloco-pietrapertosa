import { safeJsonParse } from '@/utils/safeJson';
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';

async function getPageData(lang) {
  const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
  try {
    const qs = lang === 'en' ? '?lang=en' : '';
    const qAnd = lang === 'en' ? '&lang=en' : '';
    const [pageRes, orgRes] = await Promise.all([
      fetch(`${apiUrl}/pages/comunita${qs}`, { cache: 'no-store' }),
      fetch(`${apiUrl}/directory?category=comunita${qAnd}`, { cache: 'no-store' })
    ]);
    const pageData = pageRes.ok ? await pageRes.json() : null;
    const orgData = orgRes.ok ? await orgRes.json() : { items: [] };

    return { page: pageData ? pageData.page : null, realta: orgData.items };
  } catch (error) {
    console.error("Errore recupero Comunita:", error);
    return { page: null, realta: [] };
  }
}

export default async function Comunita({ searchParams }) {
  const sp = await searchParams;
  const lang = sp?.lang || 'it';
  const { page, realta } = await getPageData(lang);
  const isEn = lang === 'en';

  if (!page) return <div className="cm-maintenance">{isEn ? "Under maintenance..." : "In manutenzione..."}</div>;

  return (
    <StoreProvider>
      <Header />
      <SectionHero title={page.hero_title} subtitle={page.hero_subtitle} img="/images/pietrapertosaComunita.jpg" />
      
      <PageIntro title={page.intro_title} text={page.intro_text} />

      <section className="cm-sec">
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
            padding: 10vh 40px; 
            gap: 60px; 
          }
          .sticky-img-box { 
            width: 100%; 
            height: clamp(400px, 60vh, 700px); 
            position: relative; 
            border-radius: 24px; 
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

        {realta.map((asso) => {
          const gallery = typeof asso.gallery === 'string' ? safeJsonParse(asso.gallery) : (asso.gallery || []);
          return (
            <div key={asso.id} className="sticky-split">
              <div className="sticky-text">
                <span className="cm-subtitle">
                  {asso.subtitle}
                </span>
                <h3 className="cm-title">
                  {asso.title}
                </h3>
                <p className="cm-desc">
                  {asso.description}
                </p>
                {asso.contact_info && <div className="cm-contact">{isEn ? "Contact:" : "Contatto:"} {asso.contact_info}</div>}
              </div>

              <div className="sticky-imgs">
                {gallery.length > 0 ? gallery.map((img, i) => (
                  <div key={i} className="sticky-img-box">
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    <img src={img} alt={`${asso.title} ${i + 1}`} className="cm-img" />
                    <div className="cm-img-overlay" />
                  </div>
                )) : (
                  <div className="sticky-img-box cm-img-empty">
                    <span className="cm-empty-text">{isEn ? "No image" : "Nessuna immagine"}</span>
                  </div>
                )}
              </div>
            </div>
          );
        })}
      </section>

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
