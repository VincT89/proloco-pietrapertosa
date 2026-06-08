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

  if (!page) return <div style={{ padding: '100px', textAlign: 'center', color: 'white' }}>{isEn ? "Under maintenance..." : "In manutenzione..."}</div>;

  return (
    <StoreProvider>
      <Header />
      <SectionHero title={page.hero_title} subtitle={page.hero_subtitle} img={page.hero_image_url} />
      
      <PageIntro title={page.intro_title} text={page.intro_text} />

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
                <span style={{ color: 'var(--gold)', textTransform: 'uppercase', letterSpacing: '3px', fontSize: '0.85rem', fontWeight: 600, display: 'inline-block', marginBottom: '24px' }}>
                  {asso.subtitle}
                </span>
                <h3 style={{ fontFamily: 'var(--font-cormorant), serif', fontSize: 'clamp(2.5rem, 4vw, 4.5rem)', color: 'var(--paper)', margin: '0 0 30px', lineHeight: 1.1 }}>
                  {asso.title}
                </h3>
                <p style={{ color: 'rgba(244,239,229,0.85)', fontSize: '1.15rem', lineHeight: 1.8, margin: 0, whiteSpace: 'pre-wrap' }}>
                  {asso.description}
                </p>
                {asso.contact_info && <div style={{ fontSize: '0.85rem', color: 'var(--stone)', marginTop: '20px' }}>{isEn ? "Contact:" : "Contatto:"} {asso.contact_info}</div>}
              </div>

              <div className="sticky-imgs">
                {gallery.length > 0 ? gallery.map((img, i) => (
                  <div key={i} className="sticky-img-box">
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    <img src={img} alt={`${asso.title} ${i + 1}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to right, var(--ink) 0%, transparent 15%, transparent 85%, var(--ink) 100%)', pointerEvents: 'none' }} />
                  </div>
                )) : (
                  <div className="sticky-img-box" style={{ background: 'rgba(255,255,255,0.05)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    <span style={{color: 'var(--stone)'}}>{isEn ? "No image" : "Nessuna immagine"}</span>
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
