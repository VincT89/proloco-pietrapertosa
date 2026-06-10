"use client";
import { safeJsonParse } from '@/utils/safeJson';
import React from 'react';
import Header from '@/components/Header';
import SectionHero from '@/components/SectionHero';
import PageIntro from '@/components/PageIntro';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { eventiData } from '@/data/eventi';
import AutoCarousel from '@/components/AutoCarousel';
import { useSearchParams } from 'next/navigation';
import { getLang } from '@/utils/lang';

export default function Eventi() {
  const [eventi, setEventi] = React.useState([]);
  const [annuali, setAnnuali] = React.useState([]);
  const [loading, setLoading] = React.useState(true);
  const [selectedGallery, setSelectedGallery] = React.useState(null);
  const searchParams = useSearchParams();
  const lang = getLang(searchParams);
  const isEn = lang === 'en';

  const formatEventDate = (start, end, isEn) => {
    if (!start) return isEn ? 'To be defined' : 'Da definire';
    const startStr = new Date(start).toLocaleDateString(isEn ? 'en-GB' : 'it-IT');
    if (end) {
      const endStr = new Date(end).toLocaleDateString(isEn ? 'en-GB' : 'it-IT');
      if (startStr !== endStr) return `${startStr} - ${endStr}`;
    }
    return startStr;
  };

  React.useEffect(() => {
    const fetchData = async () => {
      try {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
        const [evRes, annRes] = await Promise.all([
          fetch(`${apiUrl}/events${isEn ? '?lang=en' : ''}`, { cache: 'no-store' }),
          fetch(`${apiUrl}/directory?category=eventi_annuali${isEn ? '&lang=en' : ''}`, { cache: 'no-store' })
        ]);

        if (evRes.ok) {
          const data = await evRes.json();
          setEventi(data.events.filter(e => e.status === 'published'));
        }
        if (annRes.ok) {
          const annData = await annRes.json();
          setAnnuali(annData.items);
        }
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, [isEn]);

  const today = new Date();
  today.setHours(0, 0, 0, 0); // Consider today's events as future
  const futuri = eventi
    .filter(e => !e.start_date || new Date(e.start_date) >= today)
    .sort((a, b) => {
      if (!a.start_date) return 1;
      if (!b.start_date) return -1;
      return new Date(a.start_date) - new Date(b.start_date);
    });
  const passati = eventi
    .filter(e => e.start_date && new Date(e.start_date) < today)
    .sort((a, b) => new Date(b.start_date) - new Date(a.start_date));

  return (
    <StoreProvider>
      <Header />
      <SectionHero 
        title={isEn ? "Events in Pietrapertosa" : eventiData.hero.title} 
        subtitle={isEn ? "Calendar of village events" : eventiData.hero.subtitle} 
        img="/images/PietrapertosaEventi.png" 
      />
      
      {/* Eventi Annuali - Giant Cards Verticali (fissi per la tradizione) */}
      <section className="ev-sec-annuali">
        <div className="ed-wrap ev-header-wrap">
          <span className="ed-subtitle">{isEn ? "Living Traditions" : "Tradizioni vive"}</span>
          <h2 className="ed-title ev-title-nomargin">{isEn ? "Annual Events" : "Eventi Annuali"}</h2>
        </div>

        <div className="ev-card-grid">
          {annuali.map((ev, index) => {
            const gallery = typeof ev.gallery === 'string' ? safeJsonParse(ev.gallery) : (ev.gallery || []);
            return (
              <div 
                key={ev.id} 
                className={`ev-card-giant ${gallery.length > 0 ? 'is-clickable' : ''}`}
                onClick={() => { if (gallery.length > 0) setSelectedGallery(gallery); }}
              >
                <div className="ev-card-bg">
                  {gallery.length > 0 ? (
                    <AutoCarousel images={gallery} interval={3000 + index * 500} />
                  ) : (
                    <div className="ev-card-placeholder" />
                  )}
                </div>
                <div className="ev-card-overlay-right" />
                <div className="ev-card-content">
                  <div className="ev-card-content-inner">
                    <span className="ev-card-subtitle">
                      {ev.subtitle}
                    </span>
                    <h3 className="ev-card-title">
                      {ev.title}
                    </h3>
                    <p className="ev-card-desc">
                      {ev.description}
                    </p>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </section>

      {/* Eventi Dinamici (Stesso Formato di Eventi Annuali ma da DB) */}
      <section className="ev-sec-current">
        <div className="ed-wrap ev-header-wrap">
          <span className="ed-subtitle">{isEn ? "Upcoming" : "In Arrivo"}</span>
          <h2 className="ed-title ev-title-nomargin">{isEn ? `Events ${new Date().getFullYear()}` : `Eventi ${new Date().getFullYear()}`}</h2>
        </div>
        
        {loading ? (
          <div className="ev-empty-msg">
            <div className="ev-loading-msg">
              {isEn ? "Synchronizing events..." : "Sincronizzazione eventi in corso..."}
            </div>
          </div>
        ) : futuri.length === 0 ? (
          <div className="ev-empty-msg-2">
            <p className="ev-empty-italic">{isEn ? "No events scheduled yet." : "Nessun evento ancora inserito."}</p>
          </div>
        ) : (
          <div className="ev-grid-cards">
            {futuri.map((ev, index) => {
              const gallery = typeof ev.gallery === 'string' ? safeJsonParse(ev.gallery) : (ev.gallery || []);
              return (
                <div 
                  key={ev.id} 
                  className={`ev-card-normal ${gallery.length > 0 ? 'is-clickable' : ''}`}
                  onClick={() => { if (gallery.length > 0) setSelectedGallery(gallery); }}
                >
                  <div className="ev-card-normal-bg">
                    {gallery.length > 0 && (
                      <div className="ev-card-normal-bg-inner">
                        <img src={gallery[0]} alt="" className="ev-card-normal-blur" />
                      </div>
                    )}
                    {gallery.length > 0 ? (
                      <AutoCarousel images={gallery} interval={3000 + index * 500} objectFit="contain" />
                    ) : (
                      <div className="ev-card-placeholder" />
                    )}
                  </div>
                  <div className="ev-card-normal-overlay" />
                  <div className="ev-card-normal-content">
                    <div className="ev-card-normal-inner">
                      <h3 className="ev-card-title">
                        {ev.title}
                      </h3>
                      <span className="ev-card-normal-subtitle">
                        {formatEventDate(ev.start_date, ev.end_date, isEn)}
                      </span>
                      <div className="event-desc ev-card-normal-desc" dangerouslySetInnerHTML={{ __html: ev.description }}></div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </section>

      {/* Calendario e Archivio (Dinamici) */}
      <section className="ed-sec alt">
        <div className="ed-wrap">
          {loading ? (
             <div className="ev-empty-msg">
               {isEn ? "Synchronizing events..." : "Sincronizzazione eventi in corso..."}
             </div>
          ) : (
            <div className="ev-cal-wrap">
              
              {/* Calendario Eventi Futuri */}
              <div>
                <span className="ed-subtitle">{isEn ? "Scheduled" : "In Programma"}</span>
                <h2 className="ed-title ev-cal-title">{isEn ? "Calendar" : "Calendario"}</h2>
                {futuri.length === 0 ? (
                  <p className="ev-empty-msg-2 ev-empty-italic">{isEn ? "No upcoming events scheduled at the moment." : "Nessun evento futuro in programma al momento."}</p>
                ) : (
                  <div className="ev-cal-table-wrap">
                    <table className="resp-table ev-cal-table">
                      <thead>
                        <tr className="ev-cal-th-row">
                          <th className="ev-cal-th-1">{isEn ? "Date" : "Data"}</th>
                          <th className="ev-cal-th-2">{isEn ? "Title" : "Titolo"}</th>
                        </tr>
                      </thead>
                      <tbody>
                        {futuri.map(cal => {
                          const galleryList = typeof cal.gallery === 'string' ? safeJsonParse(cal.gallery) : (cal.gallery || []);
                          return (
                            <tr 
                              key={cal.id} 
                              className={`ev-cal-tr ${galleryList.length > 0 ? 'is-clickable' : ''}`}
                              onClick={() => {
                                if (galleryList.length > 0) setSelectedGallery(galleryList);
                              }}
                            >
                              <td className="ev-cal-td-1">
                                <div className="ev-cal-date">{formatEventDate(cal.start_date, cal.end_date, isEn)}</div>
                                {cal.location && <div className="ev-cal-loc">{cal.location}</div>}
                              </td>
                              <td className="ev-cal-td-2">
                                <h4 className="ev-cal-item-title">{cal.title}</h4>
                                
                              </td>
                            </tr>
                          );
                        })}
                      </tbody>
                    </table>
                  </div>
                )}
              </div>

            </div>
          )}
        </div>
      </section>

      <Footer />
      <ClientScripts />

      {/* Modale Galleria Foto */}
      {selectedGallery && (
        <div className="ev-modal" onClick={() => setSelectedGallery(null)}>
          {/* Pulsante di chiusura */}
          <button 
            onClick={(e) => { e.stopPropagation(); setSelectedGallery(null); }}
            className="ev-modal-close"
          >
            &times;
          </button>
          
          <div 
            className="ev-modal-img-wrap"
            onClick={(e) => e.stopPropagation()} // Previene la chiusura cliccando sulla foto
          >
            {selectedGallery.length > 1 ? (
              <AutoCarousel images={selectedGallery} interval={3000} objectFit="contain" />
            ) : (
              <img src={selectedGallery[0]} alt="Evento" className="ev-modal-img" />
            )}
          </div>
        </div>
      )}
    </StoreProvider>
  );
}
