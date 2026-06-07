import React from 'react';

export default function PreviewGrid({ title, subtitle, items = [], linkText, linkUrl }) {
  if (!items || items.length === 0) return null;

  // Ripristinato l'uso esatto delle classi CSS dell'HTML (.news-grid, .nc, .thumb, .date)
  return (
    <section className="paper-sec" style={{ paddingTop: 'clamp(30px, 4vw, 50px)', paddingBottom: 'clamp(30px, 4vw, 50px)' }}>
      <div className="wrap">
        <div className="sec-top">
          {subtitle && <span className="lbl">{subtitle}</span>}
        </div>
        
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', flexWrap: 'wrap', gap: '26px' }}>
          <h2 className="wr">{title}</h2>
          {linkText && linkUrl && (
            <a href={linkUrl} className="adm-link" style={{ textDecoration: 'none' }}>
              {linkText}
            </a>
          )}
        </div>

        <div className="news-grid">
          {items.map(item => (
            <a key={item.id} href={item.link || linkUrl} className="nc" style={{ textDecoration: 'none', color: 'inherit' }}>
              <div className="thumb">
                <img src={item.img || "https://placehold.co/600x400/ece6d8/5d6675?text=Preview"} alt={item.title} loading="lazy" />
              </div>
              <div className="body">
                {item.date && <div className="date">{item.date}</div>}
                <h3 className="wr">{item.title}</h3>
                {item.desc && <p>{item.desc}</p>}
              </div>
            </a>
          ))}
        </div>
      </div>
    </section>
  );
}
