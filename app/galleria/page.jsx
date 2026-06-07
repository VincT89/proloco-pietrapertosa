import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import MasonryGallery from '@/components/MasonryGallery';
import PageCTA from '@/components/PageCTA';
import Footer from '@/components/Footer';
import Lightbox from '@/components/Lightbox';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { galleryData } from '@/data/gallery';
import { Camera } from 'lucide-react';

export default function Galleria() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero title={galleryData.hero.title} subtitle={galleryData.hero.subtitle} img={galleryData.hero.img} />
      
      <section className="wrap" style={{ paddingBottom: '80px', paddingTop: '40px' }}>
        <MasonryGallery images={galleryData.images} />
      </section>

      <PageCTA subtitle="Condividi i tuoi scatti" title="Aggiungi la tua foto" text="Tagga @prolocopietrapertosa su Instagram per apparire nella nostra galleria." btnText="Seguici su Instagram" btnLink="#" icon={Camera} />

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
