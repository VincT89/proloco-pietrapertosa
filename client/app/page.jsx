import React from 'react';
import Header from '@/components/Header';
import Hero from '@/components/Hero';
import Footer from '@/components/Footer';
import ClientScripts from '@/components/ClientScripts';
import { StoreProvider } from '@/components/Store';
import { getHomeData } from '@/data/home';
import Link from 'next/link';

async function getEvents(lang) {
  try {
    const langQuery = lang === 'en' ? '?lang=en' : '';
    const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/events${langQuery}`, { cache: 'no-store' });
    if (!res.ok) return [];
    const data = await res.json();
    return data.events || [];
  } catch (error) {
    return [];
  }
}

async function getNews(lang) {
  try {
    const langQuery = lang === 'en' ? '?lang=en' : '';
    const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/news${langQuery}`, { cache: 'no-store' });
    if (!res.ok) return [];
    const data = await res.json();
    return data.news || [];
  } catch (error) {
    return [];
  }
}

function getEventImage(ev) {
  if (ev.cover_image_url) return ev.cover_image_url;
  if (ev.gallery) {
    try {
      let parsed = typeof ev.gallery === 'string' ? JSON.parse(ev.gallery) : ev.gallery;
      if (typeof parsed === 'string') parsed = JSON.parse(parsed);
      if (Array.isArray(parsed) && parsed.length > 0) return parsed[0];
    } catch (e) {}
  }
  return "https://placehold.co/400x600/14181f/d9aa63?text=Locandina";
}

const formatEventDate = (start, end, isEn, fallback) => {
  if (!start) return fallback || (isEn ? 'To be defined' : 'Da definire');
  const startStr = new Date(start).toLocaleDateString(isEn ? 'en-US' : 'it-IT');
  if (end) {
    const endStr = new Date(end).toLocaleDateString(isEn ? 'en-US' : 'it-IT');
    if (startStr !== endStr) return `${startStr} - ${endStr}`;
  }
  return startStr;
};

export default async function Home({ searchParams }) {
  const sp = await searchParams;
  const lang = sp?.lang || 'it';
  const isEn = lang === 'en';
  const data = getHomeData(lang);

  const eventiDb = await getEvents(lang);
  const notizieDb = await getNews(lang);

  // Filtriamo solo i dati pronti per la pubblicazione
  let eventiVerificati = eventiDb.filter(ev => ev.status === 'published').slice(0, 3);
  const notizieVerificate = notizieDb.filter(not => not.status === 'published').slice(0, 3);
  const scopriVerificati = data.scopri.items.filter(sc => sc.status === 'verified');

  if (eventiVerificati.length === 0) {
    eventiVerificati = data.eventi.items.filter(ev => ev.status === 'verified').slice(0, 3).map(ev => ({
      id: ev.id,
      title: ev.nome,
      title_en: ev.nome,
      cover_image_url: ev.img,
      start_date: null,
      fallback_date: ev.data
    }));
  }

  return (
    <StoreProvider>
      <Header />
      <Hero />

      {/* Prossimi Eventi */}
      {eventiVerificati.length > 0 && (
        <section className="ed-sec">
          <div className="ed-wrap">
            <div className="ed-section-header">
              <div>
                <span className="ed-subtitle">{isEn ? "Calendar" : "Calendario"}</span>
                <Link href={isEn ? "/eventi?lang=en" : "/eventi"} className="ed-link-clean">
                  <h2 className="ed-title ed-title-no-margin">{data.eventi.titolo}</h2>
                </Link>
              </div>
              <Link href={isEn ? "/eventi?lang=en" : "/eventi"} className="ed-link-more">
                {isEn ? "See all" : "Vedi tutti"}
              </Link>
            </div>
            
            <div className="ed-grid">
              {eventiVerificati.map(ev => (
                <Link key={ev.id} href={isEn ? "/eventi?lang=en" : "/eventi"} className="ed-link-clean">
                  <div className="ed-card">
                    <div className="ed-img-box tall">
                      <img src={getEventImage(ev)} alt={isEn && ev.title_en ? ev.title_en : ev.title} />
                    </div>
                    <div className="ed-glass-cap">
                      <span>{formatEventDate(ev.start_date, ev.end_date, isEn, ev.fallback_date)}</span>
                      <h3>{isEn && ev.title_en ? ev.title_en : ev.title}</h3>
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Ultime Notizie (Sempre visibile) */}
      <section className="ed-sec alt">
        <div className="ed-wrap">
          <div className="ed-section-header">
            <div>
              <span className="ed-subtitle">{isEn ? "Featured updates" : "Aggiornamenti in primo piano"}</span>
              <h2 className="ed-title ed-title-no-margin">{data.notizie.titolo}</h2>
            </div>
          </div>
          
          <div className="ed-list ed-list-wrapper">
            {notizieVerificate.length === 0 ? (
              <div className="ed-empty-msg">
                {isEn ? "There are currently no new notices on the bulletin board. Check back soon for updates." : "Al momento non ci sono nuovi avvisi in bacheca. Torna presto a trovarci per i prossimi aggiornamenti."}
              </div>
            ) : (
              notizieVerificate.map(notizia => (
                <Link key={notizia.id} href={`/notizie#avviso-${notizia.id}`} className="ed-link-clean">
                  <div className="ed-list-item ed-list-item-hover">
                    <div className="ed-item-row">
                      <div className="ed-item-date">
                        {new Date(notizia.created_at).toLocaleDateString(isEn ? 'en-US' : 'it-IT')}
                      </div>
                      <div>
                        <h4 className="ed-item-title">
                          {isEn && notizia.title_en ? notizia.title_en : notizia.title}
                        </h4>
                        <p className="ed-item-category">
                          {isEn && notizia.category_en ? notizia.category_en : notizia.category || (isEn ? "News" : "Avviso")}
                        </p>
                      </div>
                    </div>
                    <span className="ed-item-arrow">→</span>
                  </div>
                </Link>
              ))
            )}
          </div>
        </div>
      </section>

      {/* Scopri Pietrapertosa */}
      {scopriVerificati.length > 0 && (
        <section className="ed-sec alt">
          <div className="ed-wrap">
            <div className="ed-split ed-split-center">
              <div className="ed-col-center">
                <span className="ed-subtitle">Esplora il territorio</span>
                <h2 className="ed-title ed-title-large">{data.scopri.titolo}</h2>
                <p className="ed-desc-text">
                  {isEn 
                    ? "From the Arabata to the Norman-Swabian Castle, up to the exciting Flight of the Angel. Discover the places that make our village unique."
                    : "Dall'Arabata al Castello Normanno-Svevo, fino all'emozionante Volo dell'Angelo. Scopri i luoghi che rendono unico il nostro borgo."}
                </p>
                
                <ul className="ed-ul-clean">
                  {scopriVerificati.map(scopri => (
                    <li key={scopri.id} className="ed-li-flex">
                      <span className="ed-list-bullet"></span>
                      <span className="ed-li-text">{scopri.nome}</span>
                    </li>
                  ))}
                </ul>

                <div>
                  <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" className="ed-btn ed-btn-gold">
                    Vai a Borgo Racconta
                  </a>
                </div>
              </div>

              <div className="borgo-imgs">
                {scopriVerificati[0] && (
                  <div className="bi1">
                    <img src={scopriVerificati[0].img} alt={scopriVerificati[0].nome} />
                  </div>
                )}
                {scopriVerificati[1] && (
                  <div className="bi2">
                    <img src={scopriVerificati[1].img} alt={scopriVerificati[1].nome} />
                  </div>
                )}
                {scopriVerificati[2] && (
                  <div className="bi3">
                    <img src={scopriVerificati[2].img} alt={scopriVerificati[2].nome} />
                  </div>
                )}
              </div>
            </div>
          </div>
        </section>
      )}

      <Footer />
      <ClientScripts />
    </StoreProvider>
  );
}
