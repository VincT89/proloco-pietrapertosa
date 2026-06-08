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

async function getGalleryData() {
  try {
    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
    const res = await fetch(`${apiUrl}/gallery`, { cache: 'no-store' });
    if (!res.ok) return [];
    const data = await res.json();
    return data.items || [];
  } catch (error) {
    console.error('Error fetching gallery:', error);
    return [];
  }
}

export default async function Galleria({ searchParams }) {
  const sp = await searchParams;
  const isEn = sp?.lang === 'en';
  const heroData = isEn ? galleryData.en.hero : galleryData.it.hero;
  
  const rawAlbums = await getGalleryData();

  return (
    <StoreProvider>
      <Header />
      <SectionHero title={heroData.title} subtitle={heroData.subtitle} img={heroData.img} />
      
      <section className="wrap" style={{ paddingBottom: '80px', paddingTop: '40px' }}>
        {rawAlbums.length > 0 ? (
          <div style={{ display: 'flex', flexDirection: 'column', gap: '60px' }}>
            {rawAlbums.map(album => {
              const title = isEn && album.title_en ? album.title_en : album.title;
              const dateObj = album.section_date ? new Date(album.section_date) : null;
              const dateStr = dateObj ? dateObj.toLocaleDateString(isEn ? 'en-US' : 'it-IT', { month: 'long', year: 'numeric' }) : '';
              
              const images = (album.media_urls || []).map((url, idx) => ({
                id: `${album.id}-${idx}`,
                src: url,
                alt: title || 'Galleria Pietrapertosa',
                category: dateStr
              }));

              if (images.length === 0) return null;

              return (
                <div key={album.id}>
                  {(title || dateStr) && (
                    <div style={{ marginBottom: '20px', textAlign: 'left' }}>
                      {title && <h2 style={{ fontFamily: 'var(--font-cormorant), serif', fontSize: '2.5rem', color: 'var(--paper)', marginBottom: '8px' }}>{title}</h2>}
                      {dateStr && <p style={{ color: 'var(--gold)', textTransform: 'capitalize', letterSpacing: '1px', fontSize: '0.9rem' }}>{dateStr}</p>}
                    </div>
                  )}
                  <MasonryGallery images={images} />
                </div>
              );
            })}
          </div>
        ) : (
          <div style={{ textAlign: 'center', padding: '60px', color: 'var(--stone)' }}>
            {isEn ? "The gallery is currently empty." : "La galleria è momentaneamente vuota."}
          </div>
        )}
      </section>

      <PageCTA 
        subtitle={isEn ? "Share your shots" : "Condividi i tuoi scatti"} 
        title={isEn ? "Add your photo" : "Aggiungi la tua foto"} 
        text={isEn ? "Tag @prolocopietrapertosa on Instagram or Facebook to appear in our gallery." : "Tagga @prolocopietrapertosa su Instagram o Facebook per apparire nella nostra galleria."} 
        buttons={[
          { text: isEn ? "Follow us on Instagram" : "Seguici su Instagram", link: "https://www.instagram.com/proloco_pietrapertosa/", icon: Camera },
          { text: isEn ? "Follow us on Facebook" : "Seguici su Facebook", link: "https://www.facebook.com/prolocopietrapertosa1/?locale=it_IT" } // Will fallback to default or we can leave it
        ]}
      />

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
