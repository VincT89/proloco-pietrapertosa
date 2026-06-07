import React from 'react';
import { Mail } from 'lucide-react';

export default function PageCTA({ title, subtitle = "Vieni a trovarci", text, btnText, btnLink, icon: Icon = Mail }) {
  // Ripristinato l'uso esatto del blocco .cta HTML originale
  return (
    <section className="cta" style={{ paddingLeft: 0, paddingRight: 0 }}>
      <div className="bgi" style={{ backgroundImage: "url('https://commons.wikimedia.org/wiki/Special:FilePath/Chiesa%20di%20San%20Giacomo%20sullo%20sfondo%20del%20panorama%20di%20Pietrapertosa.jpg?width=1800')" }}></div>
      <div className="in">
        <span className="lbl fad" style={{ display: 'block', marginBottom: '24px' }}>{subtitle}</span>
        <h2 className="wr">{title}</h2>
        <p className="fad" style={{ margin: '24px auto 40px', maxWidth: '52ch' }}>{text}</p>
        <a href={btnLink} className="cta-line fad" style={{ opacity: 1, justifyContent: 'center' }}>
          <span className="ring">
            <Icon className="ico" />
          </span> 
          {btnText}
        </a>
      </div>
    </section>
  );
}
