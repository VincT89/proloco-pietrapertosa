import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import ExperienceCard from '@/components/ExperienceCard';
import PageCTA from '@/components/PageCTA';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { saporiData } from '@/data/sapori';
import { Utensils } from 'lucide-react';

import Lightbox from '@/components/Lightbox';

export default function Sapori() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero title={saporiData.hero.title} subtitle={saporiData.hero.subtitle} img={saporiData.hero.img} />
      
      <PageIntro title={saporiData.intro.title} text={saporiData.intro.text} />

      <section className="wrap" style={{ paddingBottom: '80px', paddingTop: '40px' }}>
        <div style={{ display: 'flex', flexDirection: 'column' }}>
          {saporiData.sapori.map((sapore, i) => (
            <ExperienceCard key={sapore.id} esperienza={sapore} reverse={i % 2 !== 0} />
          ))}
        </div>
      </section>

      <PageCTA subtitle="Tradizione a tavola" title="Vieni ad assaggiare" text="Scopri i ristoranti locali e le aziende agricole del territorio." btnText="Contattaci" btnLink="/contatti" icon={Utensils} />

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
