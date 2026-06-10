import React from 'react';
import { Mail } from 'lucide-react';

export default function PageCTA({ title, subtitle = "Vieni a trovarci", text, btnText, btnLink, icon: Icon = Mail, buttons = null }) {
  // Ripristinato l'uso esatto del blocco .cta HTML originale
  
  const renderButtons = () => {
    if (buttons && buttons.length > 0) {
      return (
        <div className="cta-buttons-wrapper">
          {buttons.map((btn, idx) => {
            const BtnIcon = btn.icon || Mail;
            return (
              <a key={idx} href={btn.link || '#'} className="cta-line fad cta-line-visible">
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
        <div className="cta-buttons-wrapper">
          <a href={btnLink || '#'} className="cta-line fad cta-line-visible">
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
    <section className="cta cta-no-padding">
      <div className="bgi cta-bgi"></div>
      <div className="in">
        <span className="lbl fad cta-subtitle">{subtitle}</span>
        <h2 className="wr">{title}</h2>
        <p className="fad cta-text">{text}</p>
        {renderButtons()}
      </div>
    </section>
  );
}
