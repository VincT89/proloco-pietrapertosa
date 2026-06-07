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

export default function ProLoco() {
  return (
    <StoreProvider>
      <Header />
      <SectionHero title={proLocoData.hero.title} subtitle={proLocoData.hero.subtitle} img={proLocoData.hero.img} />
      
      <PageIntro 
        title={proLocoData.intro.title} 
        text="Siamo un gruppo di cittadini innamorati del proprio paese, uniti dall'obiettivo di promuovere e valorizzare il patrimonio culturale, storico e umano di Pietrapertosa." 
      />

      <section className="ed-sec">
        <div className="ed-wrap">
          <div className="ed-split">
            <div>
              <span className="ed-subtitle">Le Radici</span>
              <h2 className="ed-title" style={{ fontSize: '3rem', marginBottom: '20px' }}>La Nostra Storia</h2>
              <p style={{ color: 'var(--stone)', lineHeight: '1.7', marginBottom: '40px', fontSize: '1.05rem' }}>
                Fondata nel 1998, la Pro Loco di Pietrapertosa è nata dal desiderio di un gruppo di cittadini di preservare e condividere le tradizioni del nostro borgo. Da oltre vent'anni organizziamo eventi culturali, manifestazioni storiche e momenti di aggregazione che uniscono tutte le generazioni.
              </p>
            </div>
            <div>
              <span className="ed-subtitle">Trasparenza</span>
              <h2 className="ed-title" style={{ fontSize: '3rem', marginBottom: '20px' }}>Statuto</h2>
              <p style={{ color: 'var(--stone)', lineHeight: '1.7', marginBottom: '30px', fontSize: '1.05rem' }}>
                Operiamo nel massimo rispetto delle normative per il Terzo Settore. Lo statuto ufficiale e i verbali delle assemblee sono sempre a disposizione dei nostri tesserati.
              </p>
              <a href="#" className="ed-btn" style={{ padding: '16px 24px' }}>
                <FileText size={18} style={{ marginRight: '10px' }} />
                Scarica lo Statuto (PDF)
              </a>
            </div>
          </div>
        </div>
      </section>

      <Squadra />

      <section className="ed-sec paper">
        <div className="ed-wrap">
          <div className="ed-form-box">
            <div style={{ textAlign: 'center', marginBottom: '50px' }}>
              <span className="ed-subtitle">Partecipa</span>
              <h2 className="ed-title" style={{ marginBottom: '20px' }}>Diventa Socio</h2>
              <p style={{ color: 'var(--stone)', maxWidth: '600px', margin: '0 auto', fontSize: '1.1rem' }}>
                Unisciti a noi per supportare attivamente la comunità. Compila la richiesta per ricevere la tessera della Pro Loco e partecipare alle assemblee.
              </p>
            </div>
            <form style={{ maxWidth: '700px', margin: '0 auto', display: 'flex', flexDirection: 'column', gap: '30px' }} onSubmit={(e) => e.preventDefault()}>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))', gap: '30px' }}>
                <input type="text" placeholder="Nome" className="ed-input" />
                <input type="text" placeholder="Cognome" className="ed-input" />
              </div>
              <input type="email" placeholder="Email" className="ed-input" />
              <input type="text" placeholder="Codice Fiscale" className="ed-input" />
              <div style={{ textAlign: 'center' }}>
                <button type="submit" className="ed-btn">Invia Richiesta Tesseramento</button>
              </div>
            </form>
          </div>
        </div>
      </section>

      <section className="ed-sec alt" style={{ paddingBottom: '140px' }}>
        <div className="ed-wrap">
          <div className="cont-wrap">
            <div className="cont">
              <div className="cc" style={{ border: 'none', borderBottom: '1px solid rgba(255,255,255,0.05)', borderRight: '1px solid rgba(255,255,255,0.05)' }}>
                <MapPin className="ico" style={{ color: 'var(--gold)' }} />
                <h4 style={{ fontFamily: '"Newsreader", serif', fontSize: '1.6rem', color: 'var(--paper)', margin: '10px 0' }}>La Sede</h4>
                <p style={{ color: 'var(--stone)' }}>Via della Speranza, 159<br/>85010 Pietrapertosa (PZ)</p>
              </div>
              <div className="cc" style={{ border: 'none', borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                <FileText className="ico" style={{ color: 'var(--gold)' }} />
                <h4 style={{ fontFamily: '"Newsreader", serif', fontSize: '1.6rem', color: 'var(--paper)', margin: '10px 0' }}>Dati Fiscali</h4>
                <p style={{ color: 'var(--stone)' }}>P.Iva: 01925320762<br/>Cod. Fiscale: 96028030763</p>
              </div>
              <div className="cc" style={{ border: 'none', borderRight: '1px solid rgba(255,255,255,0.05)' }}>
                <Phone className="ico" style={{ color: 'var(--gold)' }} />
                <h4 style={{ fontFamily: '"Newsreader", serif', fontSize: '1.6rem', color: 'var(--paper)', margin: '10px 0' }}>Chiamaci</h4>
                <p><a href="tel:+393208337801" style={{ color: 'var(--gold-soft)', textDecoration: 'none' }}>320 8337801</a></p>
              </div>
              <div className="cc" style={{ border: 'none' }}>
                <Mail className="ico" style={{ color: 'var(--gold)' }} />
                <h4 style={{ fontFamily: '"Newsreader", serif', fontSize: '1.6rem', color: 'var(--paper)', margin: '10px 0' }}>Scrivici</h4>
                <p><a href="mailto:prolocopietrapertosa@gmail.com" style={{ color: 'var(--gold-soft)', textDecoration: 'none' }}>prolocopietrapertosa@gmail.com</a></p>
              </div>
            </div>
            <div className="cont-photo" style={{ position: 'relative', overflow: 'hidden' }}>
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12046.208151528646!2d16.0505193!3d40.5183377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1338d41edec1d0db%3A0x88f572a1cd3de995!2s85010%20Pietrapertosa%20PZ!5e0!3m2!1sit!2sit!4v1700000000000!5m2!1sit!2sit" 
                style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', border: 0, filter: 'grayscale(0.4) contrast(1.1) brightness(0.9)' }} 
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
