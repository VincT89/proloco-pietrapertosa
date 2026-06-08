import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import PhotoCollage from '@/components/PhotoCollage';
import PageCTA from '@/components/PageCTA';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { borgoData } from '@/data/borgo';
import { Map } from 'lucide-react';
import ExperienceCard from '@/components/ExperienceCard';

import Lightbox from '@/components/Lightbox';

export default function IlBorgo() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero title={borgoData.hero.title} subtitle={borgoData.hero.subtitle} img={borgoData.hero.img} />
      
      <div className="wrap" style={{ position: 'relative', zIndex: 10, marginTop: '-60px', marginBottom: '40px' }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '20px' }}>
          {borgoData.stats.map((s, i) => (
            <div key={i} className="fad parchment-badge" style={{ 
              textAlign: 'center', 
              padding: '24px 16px', 
              background: 'linear-gradient(135deg, #fdfbf7 0%, #f4efe5 100%)', 
              boxShadow: '0 12px 30px rgba(0,0,0,0.4)', 
              border: '1px solid #e2d5c3', 
              borderRadius: '2px', 
              position: 'relative',
              overflow: 'hidden',
              transition: 'transform 0.4s ease, box-shadow 0.4s ease'
            }}>
              <div style={{ position: 'absolute', inset: '5px', border: '1px solid rgba(165, 116, 46, 0.2)', pointerEvents: 'none' }}></div>
              <div style={{ fontSize: '2.4rem', color: '#1a1a1a', fontFamily: "'Cormorant Garamond', serif", marginBottom: '8px', lineHeight: '1', fontWeight: '500' }}>{s.value}</div>
              <div style={{ color: '#5d6675', textTransform: 'uppercase', fontSize: '0.65rem', letterSpacing: '0.25em', fontWeight: '700' }}>{s.label}</div>
            </div>
          ))}
        </div>
      </div>

      <PageIntro title={borgoData.intro.title} text={borgoData.intro.text} />

      <section className="wrap" style={{ paddingBottom: '30px' }}>

        <div style={{ display: 'flex', flexDirection: 'column' }}>
          {borgoData.highlights.map((h, i) => (
            <ExperienceCard key={h.id} esperienza={h} reverse={i % 2 !== 0} />
          ))}
        </div>
      </section>


      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
