import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import PageCTA from '@/components/PageCTA';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { contattiData } from '@/data/contatti';
import { Home, MapPin, Phone, Mail, Share2 } from 'lucide-react';
import Squadra from '@/components/Squadra';
import Lightbox from '@/components/Lightbox';

export default function Contatti() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero title={contattiData.hero.title} subtitle={contattiData.hero.subtitle} img={contattiData.hero.img} />
      
      <PageIntro title="Siamo qui per te" text="La Pro Loco di Pietrapertosa è sempre a disposizione per aiutarti a pianificare la tua visita. Non esitare a contattarci per qualsiasi informazione su eventi, escursioni e accoglienza." />

      <section className="wrap" style={{ paddingBottom: '100px' }}>
        <div className="cont-wrap">
          <div className="cont">
            <div className="cc">
              <MapPin className="ico" />
              <h4>Sede legale</h4>
              <p>{contattiData.info.address}</p>
              {contattiData.info.piva && (
                <p style={{ marginTop: '8px', fontSize: '0.85rem', color: 'var(--stone)' }}>
                  P.Iva: {contattiData.info.piva}<br/>
                  Cod. Fiscale: {contattiData.info.cf}
                </p>
              )}
            </div>
            <div className="cc">
              <Phone className="ico" />
              <h4>Chiamaci</h4>
              <p><a href={`tel:${contattiData.info.phone.replace(/ /g, '')}`}>{contattiData.info.phone}</a></p>
            </div>
            <div className="cc">
              <Mail className="ico" />
              <h4>Scrivici</h4>
              <p><a href={`mailto:${contattiData.info.email}`}>{contattiData.info.email}</a></p>
            </div>
            <div className="cc">
              <Share2 className="ico" />
              <h4>Seguici</h4>
              <p>
                {contattiData.social.map(s => (
                  <a key={s.name} href={s.link} target="_blank" rel="noreferrer" style={{ display: 'block' }}>{s.name}</a>
                ))}
              </p>
            </div>
          </div>
          <div className="cont-photo">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12046.208151528646!2d16.0505193!3d40.5183377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1338d41edec1d0db%3A0x88f572a1cd3de995!2s85010%20Pietrapertosa%20PZ!5e0!3m2!1sit!2sit!4v1700000000000!5m2!1sit!2sit" 
              style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', border: 0, filter: 'grayscale(0.4) contrast(1.1) brightness(0.9)' }} 
              allowFullScreen="" 
              loading="lazy" 
              referrerPolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </section>

      <Squadra />

      <PageCTA subtitle="Scopri il territorio" title="Torna alla Home" text="Oppure continua a esplorare il nostro meraviglioso borgo." btnText="Home" btnLink="/" icon={Home} />

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
