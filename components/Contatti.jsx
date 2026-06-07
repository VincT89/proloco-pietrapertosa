import React from 'react';
import { MapPin, Mail, Phone, Route } from 'lucide-react';

export default function Contatti() {
  return (
    <section id="contatti">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">scriveteci, rispondiamo davvero</span>
        </div>

        <h2 className="wr" style={{ marginBottom: "clamp(36px,5vw,60px)" }}>
          Vieni a <em>trovarci</em>
        </h2>

        <div className="cont-wrap">
          <div className="cont-photo cur">
            <img
              src="https://commons.wikimedia.org/wiki/Special:FilePath/Castello%20di%20Pietrapertosa%201.jpg?width=900"
              alt="Il Castello di Pietrapertosa"
              loading="lazy"
            />
          </div>

          <div className="cont">
            <div className="cc fad">
              <MapPin className="ico" />
              <h4>Dove siamo</h4>
              <p>Associazione Pro Loco Pietrapertosana<br />Via della Speranza, 159<br />85010 Pietrapertosa (PZ)</p>
            </div>

            <div className="cc fad">
              <Mail className="ico" />
              <h4>Email</h4>
              <p><a href="mailto:prolocopietrapertosa@tiscali.it">prolocopietrapertosa@tiscali.it</a><br />Rispondiamo davvero, promesso</p>
            </div>

            <div className="cc fad">
              <Phone className="ico" />
              <h4>Telefono</h4>
              <p><a href="tel:+390971983529">0971 983529</a> · <a href="tel:+393208337801">320 8337801</a><br />Punto informazioni turistiche</p>
            </div>

            <div className="cc fad">
              <Route className="ico" />
              <h4>Come arrivare</h4>
              <p>SS407 Basentana, uscita Campomaggiore, poi su per la montagna.<br />Da Potenza ~45 min · da Matera ~1 ora</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}