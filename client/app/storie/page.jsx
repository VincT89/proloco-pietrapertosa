import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import StoryCard from '@/components/StoryCard';
import PageCTA from '@/components/PageCTA';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { storieData } from '@/data/storie';
import { BookOpen } from 'lucide-react';

import Lightbox from '@/components/Lightbox';

export default async function Storie({ searchParams }) {
  const sp = await searchParams;
  const isEn = sp?.lang === 'en';
  const data = isEn ? storieData.en : storieData.it;

  return (
    <StoreProvider>
      <Header />
      <SectionHero title={data.hero.title} subtitle={data.hero.subtitle} img={data.hero.img} />
      
      <PageIntro title={data.intro.title} text={data.intro.text} />

      <section className="wrap" style={{ paddingBottom: '80px', paddingTop: '120px' }}>
        <div style={{ display: 'flex', flexDirection: 'column' }}>
          {data.storie.map((storia, i) => (
            <StoryCard key={storia.id} storia={storia} reverse={i % 2 !== 0} />
          ))}
        </div>
      </section>

      <PageCTA 
        subtitle={isEn ? "Myths and memories" : "Miti e memorie"} 
        title={isEn ? "Come listen to our stories" : "Vieni ad ascoltare le nostre storie"} 
        text={isEn ? "We wait for you in the heart of the Lucanian Dolomites." : "Ti aspettiamo nel cuore delle Dolomiti Lucane."} 
        btnText={isEn ? "Discover Pro Loco" : "Scopri la Pro Loco"} 
        btnLink="/pro-loco" 
        icon={BookOpen} 
      />

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
