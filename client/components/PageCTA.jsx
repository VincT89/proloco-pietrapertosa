import React from 'react';
import { Mail } from 'lucide-react';

export default function PageCTA({ title, subtitle = "Vieni a trovarci", text, btnText, btnLink, icon: Icon = Mail, buttons = null }) {
  // Ripristinato l'uso esatto del blocco .cta HTML originale
  
  const renderButtons = () => {
    if (buttons && buttons.length > 0) {
      return (
        <div style={{ display: 'flex', gap: '20px', justifyContent: 'center', flexWrap: 'wrap' }}>
          {buttons.map((btn, idx) => {
            const BtnIcon = btn.icon || Mail;
            return (
              <a key={idx} href={btn.link || '#'} className="cta-line fad" style={{ opacity: 1 }}>
                <span className="ring">
                  <BtnIcon className="ico" />
                </span> 
                {btn.text}
              </a>
            );
          })}
        </div>
      );
    }
    
    // Fallback single button
    if (btnText) {
      return (
        <div style={{ display: 'flex', justifyContent: 'center' }}>
          <a href={btnLink || '#'} className="cta-line fad" style={{ opacity: 1 }}>
            <span className="ring">
              <Icon className="ico" />
            </span> 
            {btnText}
          </a>
        </div>
      );
    }
    
    return null;
  };

  return (
    <section className="cta" style={{ paddingLeft: 0, paddingRight: 0 }}>
      <div className="bgi" style={{ backgroundImage: "url('https://commons.wikimedia.org/wiki/Special:FilePath/Chiesa%20di%20San%20Giacomo%20sullo%20sfondo%20del%20panorama%20di%20Pietrapertosa.jpg?width=1800')" }}></div>
      <div className="in">
        <span className="lbl fad" style={{ display: 'block', marginBottom: '24px' }}>{subtitle}</span>
        <h2 className="wr">{title}</h2>
        <p className="fad" style={{ margin: '24px auto 40px', maxWidth: '52ch' }}>{text}</p>
        {renderButtons()}
      </div>
    </section>
  );
}
