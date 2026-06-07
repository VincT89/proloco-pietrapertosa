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

export default function Storie() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero title={storieData.hero.title} subtitle={storieData.hero.subtitle} img={storieData.hero.img} />
      
      <PageIntro title={storieData.intro.title} text={storieData.intro.text} />

      <section className="wrap" style={{ paddingBottom: '80px', paddingTop: '120px' }}>
        <div style={{ display: 'flex', flexDirection: 'column' }}>
          {storieData.storie.map((storia, i) => (
            <StoryCard key={storia.id} storia={storia} reverse={i % 2 !== 0} />
          ))}
        </div>
      </section>

      <PageCTA subtitle="Miti e memorie" title="Vieni ad ascoltare le nostre storie" text="Ti aspettiamo nel cuore delle Dolomiti Lucane." btnText="Scopri la Pro Loco" btnLink="/pro-loco" icon={BookOpen} />

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
