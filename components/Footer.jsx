import Link from "next/link";

export default function Footer() {
  return (
    <footer>
      <div className="f-big" id="fBig">
        Pietra<em>pertosa</em>
      </div>

      <div className="f-cols">
        <div className="fb" style={{ display: "flex", gap: "18px", alignItems: "flex-start", flexDirection: "column" }}>
          <img src="/images/logo.png" alt="" style={{ height: 70, width: "auto", flexShrink: 0 }} />
          <div>
            <b>Pro Loco Pietrapertosana</b>
            <ul className="fu">
              <li><a href="mailto:prolocopietrapertosa@gmail.com">prolocopietrapertosa@gmail.com</a></li>
              <li>320 8337801</li>
            </ul>
            <p>
              Associazione no profit per la promozione turistica e culturale del borgo,
              nel Parco delle Piccole Dolomiti Lucane.
            </p>
            <div style={{ marginTop: "16px", fontSize: "0.8rem", color: "var(--stone)", lineHeight: "1.6" }}>
              Via della Speranza, 159<br/>
              85010 Pietrapertosa (PZ)<br/>
              P.Iva: 01925320762<br/>
              Cod. Fiscale: 96028030763
            </div>
          </div>
        </div>

        <div>
          <h5>Esplora</h5>
          <Link href="/">Home</Link>
          <Link href="/eventi">Eventi</Link>
          <Link href="/comunita">Comunità</Link>
          <Link href="/territorio">Territorio</Link>
          <Link href="/vivere">Vivere</Link>
          <Link href="/scopri">Scopri</Link>
          <Link href="/pro-loco">Pro Loco</Link>
        </div>

        <div>
          <h5>Territorio</h5>
          <a href="https://www.volodellangelo.com" target="_blank" rel="noopener noreferrer">Volo dell&apos;Angelo</a>
          <a href="https://www.parcogallipolicognato.it" target="_blank" rel="noopener noreferrer">Parco Gallipoli Cognato</a>
          <a href="https://www.basilicataturistica.it" target="_blank" rel="noopener noreferrer">APT Basilicata</a>
          <a href="https://borghipiubelliditalia.it" target="_blank" rel="noopener noreferrer">Borghi più belli d&apos;Italia</a>
        </div>

        <div>
          <h5>Seguici</h5>
          <a href="https://www.facebook.com/prolocopietrapertosa1/" target="_blank" rel="noopener noreferrer">Facebook</a>
          <a href="#">Instagram</a>
          <a href="https://prolocopietrapertosa.org" target="_blank" rel="noopener noreferrer">Il nostro archivio</a>
        </div>
      </div>

      <div className="f-bot">
        <span>© 2026 Pro Loco Pietrapertosa</span>
        <span>
          <i style={{ color: "var(--gold-soft)" }}>fatto con il cuore, a 1.088 metri</i>
        </span>
      </div>
    </footer>
  );
}