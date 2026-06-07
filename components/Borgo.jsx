import React from 'react';
import { Castle, Home, Church, TreePine } from 'lucide-react';

export default function Borgo() {
  return (
    <section className="borgo paper-sec" id="borgo">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">il paese</span>
        </div>

        <div className="borgo-grid">
          <div className="borgo-imgs">
            <div className="bi1 cur">
              <img src="/images/immaginePaese.jpg" alt="Scorcio del borgo" loading="lazy" />
            </div>

            <div className="bi2 cur">
              <img
                src="/images/arabata01.jpg"
                alt="Il rione Arabata"
                loading="lazy"
              />
            </div>

            <div className="bi3 cur">
              <img
                src="/images/campanileSanGiacomo.jpg"
                alt="Campanile di San Giacomo"
                loading="lazy"
              />
            </div>

            <div className="bi-cap fad">una storia scritta nella roccia</div>
          </div>

          <div className="borgo-txt">
            <h2 className="wr">Scolpito nella <em>pietra viva</em></h2>

            <p className="dc fad">
              Il nome antico era <b>Pietraperciata</b>, &quot;pietra forata&quot;:
              c&apos;è davvero una rupe bucata da parte a parte all&apos;ingresso del paese.
              E le case? Costruite sulla roccia nuda — in certi vicoli il muro di casa è la montagna stessa.
            </p>

            <p className="fad">
              Qui sono passati tutti: greci, romani, gli arabi del <b>re Bomar</b>{" "}nell&apos;838
              che ci hanno lasciato il rione <b>Arabata</b>, poi normanni e svevi.
              Ognuno ha lasciato qualcosa — e noi ve lo raccontiamo volentieri.
            </p>

            <div className="b-list fad">
              <div>
                <Castle className="ico" />
                <span><b>Il Castello saraceno</b> — si sale per una scalinata scavata nella roccia. La vista vale la fatica, promesso.</span>
              </div>

              <div>
                <Home className="ico" />
                <span><b>L&apos;Arabata</b> — vicoli ciechi, scalette e case che spuntano dalla rupe. Perdetevici.</span>
              </div>

              <div>
                <Church className="ico" />
                <span><b>San Giacomo</b> e il Convento di San Francesco del 1474</span>
              </div>

              <div>
                <TreePine className="ico" />
                <span><b>Parco di Gallipoli Cognato</b> — boschi, guglie e l&apos;antica Croccia Cognato</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}