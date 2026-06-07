import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import ExperienceCard from '@/components/ExperienceCard';
import PageCTA from '@/components/PageCTA';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { esperienzeData } from '@/data/esperienze';
import { CalendarCheck } from 'lucide-react';

import Lightbox from '@/components/Lightbox';

export default function Esperienze() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero title={esperienzeData.hero.title} subtitle={esperienzeData.hero.subtitle} img={esperienzeData.hero.img} />
      
      <PageIntro title={esperienzeData.intro.title} text={esperienzeData.intro.text} />

      <section className="wrap" style={{ paddingBottom: '60px' }}>
        <div style={{ display: 'flex', flexDirection: 'column' }}>
          {esperienzeData.esperienze.map((exp, i) => (
            <ExperienceCard key={exp.id} esperienza={exp} reverse={i % 2 !== 0} />
          ))}
        </div>
      </section>

      <PageCTA subtitle="Vivi l'emozione" title="Prenota la tua Esperienza" text="Contattaci per informazioni sui biglietti e sulle guide locali." btnText="Contattaci" btnLink="/contatti" icon={CalendarCheck} />

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
