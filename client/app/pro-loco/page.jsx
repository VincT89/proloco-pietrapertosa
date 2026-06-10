"use client";
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Squadra from '@/components/Squadra';
import Footer from '@/components/Footer';
import Lightbox from '@/components/Lightbox';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { proLocoData } from '@/data/pro-loco';
import { MapPin, Phone, Mail, FileText } from 'lucide-react';
import { useSearchParams } from 'next/navigation';
import { getLang } from '@/utils/lang';

export default function ProLoco() {
  const searchParams = useSearchParams();
  const lang = getLang(searchParams);
  const isEn = lang === 'en';
  const data = isEn ? proLocoData.en : proLocoData.it;

  return (
    <StoreProvider>
      <Header />
      <SectionHero title={data.hero.title} subtitle={data.hero.subtitle} img={data.hero.img} />
      
      <PageIntro 
        title={data.intro.title} 
        text={isEn ? "We are a group of citizens in love with our village, united by the goal of promoting and enhancing the cultural, historical and human heritage of Pietrapertosa." : "Siamo un gruppo di cittadini innamorati del proprio paese, uniti dall'obiettivo di promuovere e valorizzare il patrimonio culturale, storico e umano di Pietrapertosa."} 
      />

      <section className="ed-sec">
        <div className="ed-wrap">
          <div className="ed-split">
            <div>
              <span className="ed-subtitle">{isEn ? "The Roots" : "Le Radici"}</span>
              <h2 className="ed-title proloco-title">{isEn ? "Our History" : "La Nostra Storia"}</h2>
              <p className="proloco-desc">
                {isEn ? "Founded in 1998, the Pro Loco of Pietrapertosa was born from the desire of a group of citizens to preserve and share the traditions of our village. For over twenty years we have been organizing cultural events, historical reenactments and moments of aggregation that unite all generations." : "Fondata nel 1998, la Pro Loco di Pietrapertosa è nata dal desiderio di un gruppo di cittadini di preservare e condividere le tradizioni del nostro borgo. Da oltre vent'anni organizziamo eventi culturali, manifestazioni storiche e momenti di aggregazione che uniscono tutte le generazioni."}
              </p>
            </div>
            <div>
              <span className="ed-subtitle">{isEn ? "Transparency" : "Trasparenza"}</span>
              <h2 className="ed-title proloco-title">{isEn ? "Statute" : "Statuto"}</h2>
              <p className="proloco-desc-short">
                {isEn ? "We operate in full compliance with the regulations for the Third Sector. The official statute and the minutes of the assemblies are always available to our members." : "Operiamo nel massimo rispetto delle normative per il Terzo Settore. Lo statuto ufficiale e i verbali delle assemblee sono sempre a disposizione dei nostri tesserati."}
              </p>
              <a href="#" className="ed-btn proloco-btn-pad">
                <FileText size={18} className="proloco-btn-icon" />
                {isEn ? "View the Statute (PDF)" : "Visualizza lo Statuto (PDF)"}
              </a>
            </div>
          </div>
        </div>
      </section>

      <Squadra />

      <section className="ed-sec paper">
        <div className="ed-wrap">
          <div className="ed-form-box">
            <div className="proloco-center-box">
              <span className="ed-subtitle">{isEn ? "Participate" : "Partecipa"}</span>
              <h2 className="ed-title proloco-form-title">{isEn ? "Become a Member" : "Diventa Socio"}</h2>
              <p className="proloco-center-desc">
                {isEn ? "Join us to actively support the community. Fill out the request to receive the Pro Loco membership card and participate in the assemblies." : "Unisciti a noi per supportare attivamente la comunità. Compila la richiesta per ricevere la tessera della Pro Loco e partecipare alle assemblee."}
              </p>
            </div>
            <form className="proloco-form" onSubmit={(e) => e.preventDefault()}>
              <div className="proloco-form-grid">
                <input type="text" placeholder={isEn ? "First Name" : "Nome"} className="ed-input" />
                <input type="text" placeholder={isEn ? "Last Name" : "Cognome"} className="ed-input" />
              </div>
              <input type="email" placeholder="Email" className="ed-input" />
              <input type="text" placeholder={isEn ? "Tax Code" : "Codice Fiscale"} className="ed-input" />
              <div className="proloco-form-submit">
                <button type="submit" className="ed-btn">{isEn ? "Send Membership Request" : "Invia Richiesta Tesseramento"}</button>
              </div>
            </form>
          </div>
        </div>
      </section>

      <section className="ed-sec alt proloco-contact-sec">
        <div className="ed-wrap">
          <div className="cont-wrap">
            <div className="cont">
              <div className="cc proloco-cc">
                <MapPin className="ico proloco-cc-icon" />
                <h4 className="proloco-cc-title">{isEn ? "Headquarters" : "La Sede"}</h4>
                <p className="proloco-cc-text">Via della Speranza, 159<br/>85010 Pietrapertosa (PZ)</p>
              </div>
              <div className="cc proloco-cc-bot">
                <FileText className="ico proloco-cc-icon" />
                <h4 className="proloco-cc-title">{isEn ? "Tax Info" : "Dati Fiscali"}</h4>
                <p className="proloco-cc-text">P.Iva: 01925320762<br/>Cod. Fiscale: 96028030763</p>
              </div>
              <div className="cc proloco-cc-right">
                <Phone className="ico proloco-cc-icon" />
                <h4 className="proloco-cc-title">{isEn ? "Call Us" : "Chiamaci"}</h4>
                <p><a href="tel:+393429896770" className="proloco-cc-link">342 989 6770</a></p>
              </div>
              <div className="cc proloco-cc-none">
                <Mail className="ico proloco-cc-icon" />
                <h4 className="proloco-cc-title">{isEn ? "Write Us" : "Scrivici"}</h4>
                <p><a href="mailto:prolocopietrapertosa@gmail.com" className="proloco-cc-link">prolocopietrapertosa@gmail.com</a></p>
              </div>
            </div>
            <div className="cont-photo proloco-map-wrap">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12046.208151528646!2d16.0505193!3d40.5183377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1338d41edec1d0db%3A0x88f572a1cd3de995!2s85010%20Pietrapertosa%20PZ!5e0!3m2!1sit!2sit!4v1700000000000!5m2!1sit!2sit" 
                className="proloco-map-frame"
                allowFullScreen="" 
                loading="lazy" 
                referrerPolicy="no-referrer-when-downgrade"
              ></iframe>
            </div>
          </div>
        </div>
      </section>

      <Footer />
      <Lightbox />
      <ClientScripts />
    </StoreProvider>
  );
}
