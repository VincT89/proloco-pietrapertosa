import React from 'react';
import { Mail } from 'lucide-react';

export default function CTA() {
  return (
    <section className="cta" id="proloco" style={{ paddingLeft: 0, paddingRight: 0 }}>
      <div
        className="bgi"
        style={{
          backgroundImage: "url('/images/panorama.jpg')",
        }}
      ></div>

      <div className="in">
        <span className="lbl fad" style={{ display: "block", marginBottom: "24px" }}>
          Unisciti alla squadra
        </span>

        <h2 className="wr">
          Il borgo vive grazie a <em>chi lo ama</em>
        </h2>

        <p className="fad">
          La Pro Loco è fatta di volontari: chi cucina, chi accompagna i gruppi,
          chi monta il palco. Se volete dare una mano alle feste, proporre
          un&apos;idea o semplicemente conoscerci, scriveteci o passate in sede.
        </p>

        <a
          href="mailto:prolocopietrapertosa@tiscali.it"
          className="cta-line fad"
          style={{ opacity: 1, justifyContent: "center" }}
        >
          <span className="ring">
            <Mail className="ico" />
          </span>
          Scriveteci
        </a>
      </div>
    </section>
  );
}