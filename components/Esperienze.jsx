export default function Esperienze() {
  return (
    <section id="esperienze">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">vivere il territorio</span>
        </div>

        {/* 1. Volo dell'Angelo */}
        <div className="exp-row">
          <div className="exp-img cur">
            <div
              className="bgi"
              style={{ backgroundImage: "url('/images/voloAngelo.jpg')" }}
            ></div>
          </div>

          <div className="exp-txt">
            <span className="lbl-mut fad">Per i coraggiosi</span>
            <h3 className="wr">Il <em>Volo</em> dell&apos;Angelo</h3>
            <p className="fad">
              Un cavo d&apos;acciaio tra il nostro paese e Castelmezzano: vi imbragano,
              vi agganciano e via — un chilometro e mezzo sopra le guglie. All&apos;inizio
              si urla, poi non vorreste più scendere.
            </p>

            <div className="exp-data fad">
              <div><div className="v">~1,5 km</div><div className="c">di volo</div></div>
              <div><div className="v">100+ km/h</div><div className="c">velocità</div></div>
              <div><div className="v">2 linee</div><div className="c">andata e ritorno</div></div>
            </div>
          </div>
        </div>

        {/* 2. Via Ferrata */}
        <div className="exp-row rev">
          <div className="exp-img cur">
            <div
              className="bgi"
              style={{ backgroundImage: "url('/images/viaFerrata.jpg')" }}
            ></div>
          </div>

          <div className="exp-txt">
            <span className="lbl-mut fad">Mani sulla roccia</span>
            <h3 className="wr">Le vie <em>ferrate</em></h3>
            <p className="fad">
              Sentieri attrezzati che vi portano nel cuore di pietra del parco. Due percorsi, ponti tibetani sospesi nel vuoto e la roccia arenaria sotto le dita. Un&apos;esperienza per chi ama conquistare la vetta un moschettone alla volta.
            </p>
            <div className="exp-data fad">
              <div><div className="v">2</div><div className="c">percorsi</div></div>
              <div><div className="v">Ponti</div><div className="c">tibetani</div></div>
              <div><div className="v">Panorami</div><div className="c">unici</div></div>
            </div>
          </div>
        </div>

        {/* 3. Cammini e Sentieri */}
        <div className="exp-row">
          <div className="exp-img cur">
            <div
              className="bgi"
              style={{ backgroundImage: "url('/images/percorsi.jpg')" }}
            ></div>
          </div>

          <div className="exp-txt">
            <span className="lbl-mut fad">Turismo lento</span>
            <h3 className="wr">Cammini ed <em>Escursioni</em></h3>
            <p className="fad">
              Per chi preferisce il ritmo lento del respiro. Il Percorso delle Sette Pietre, le passeggiate nei boschi e gli antichi tratturi. Un'immersione totale nel silenzio e nei profumi delle Dolomiti Lucane.
            </p>

            <div className="exp-data fad">
              <div><div className="v">Natura</div><div className="c">incontaminata</div></div>
              <div><div className="v">Trekking</div><div className="c">per tutti</div></div>
              <div><div className="v">Silenzio</div><div className="c">assoluto</div></div>
            </div>
          </div>
        </div>

        {/* 4. Scoperta dei vicoli */}
        <div className="exp-row rev">
          <div className="exp-img cur">
            <div
              className="bgi"
              style={{ backgroundImage: "url('/images/arabata.jpeg')" }}
            ></div>
          </div>

          <div className="exp-txt">
            <span className="lbl-mut fad">Esperienze culturali</span>
            <h3 className="wr">Scoperta dei <em>vicoli</em></h3>
            <p className="fad">
              Passeggiare senza fretta tra l'Arabata e il Castello, ammirando i portali in pietra, perdendosi nelle piazzette nascoste e lasciandosi raccontare storie antiche dagli abitanti affacciati ai balconi.
            </p>
            <div className="exp-data fad">
              <div><div className="v">Storia</div><div className="c">millenaria</div></div>
              <div><div className="v">Architettura</div><div className="c">rupestre</div></div>
              <div><div className="v">Incontri</div><div className="c">autentici</div></div>
            </div>
          </div>
        </div>

      </div>
    </section>
  );
}