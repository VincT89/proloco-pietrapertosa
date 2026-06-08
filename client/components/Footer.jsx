"use client";
import Link from "next/link";
import { usePathname } from "next/navigation";

export default function Footer() {
  const pathname = usePathname() || '';
  const isEn = pathname.startsWith('/en');

  const itToEn = {
    '/eventi': '/events',
    '/comunita': '/community',
    '/notizie': '/news',
    '/territorio': '/territory',
    '/sapori': '/tastes',
    '/scopri': '/discover',
    '/storie': '/stories',
    '/galleria': '/gallery',
    '/pro-loco': '/pro-loco',
    '/': ''
  };

  const getRoute = (path) => {
    if (isEn) {
      return `/en${itToEn[path] !== undefined ? itToEn[path] : path}`;
    }
    return path;
  };

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
              <li>342 989 6770</li>
            </ul>
            <p>
              {isEn ? "Non-profit association for the touristic and cultural promotion of the village, in the Park of the Lucanian Dolomites." : "Associazione no profit per la promozione turistica e culturale del borgo, nel Parco delle Piccole Dolomiti Lucane."}
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
          <h5>{isEn ? "Explore" : "Esplora"}</h5>
          <Link href={getRoute("/")}>Home</Link>
          <Link href={getRoute("/eventi")}>{isEn ? "Events" : "Eventi"}</Link>
          <Link href={getRoute("/comunita")}>{isEn ? "Community" : "Comunità"}</Link>
          <Link href={getRoute("/territorio")}>{isEn ? "Territory" : "Territorio"}</Link>
          <Link href={getRoute("/sapori")}>{isEn ? "Tastes" : "Sapori"}</Link>
          <Link href={getRoute("/scopri")}>{isEn ? "Discover" : "Scopri"}</Link>
          <Link href={getRoute("/pro-loco")}>Pro Loco</Link>
        </div>

        <div>
          <h5>{isEn ? "Territory" : "Territorio"}</h5>
          <a href="https://www.volodellangelo.com" target="_blank" rel="noopener noreferrer">Volo dell&apos;Angelo</a>
          <a href="https://www.parcogallipolicognato.it" target="_blank" rel="noopener noreferrer">Parco Gallipoli Cognato</a>
          <a href="https://www.basilicataturistica.it" target="_blank" rel="noopener noreferrer">APT Basilicata</a>
          <a href="https://borghipiubelliditalia.it" target="_blank" rel="noopener noreferrer">Borghi più belli d&apos;Italia</a>
        </div>

        <div>
          <h5>{isEn ? "Follow us" : "Seguici"}</h5>
          <a href="https://www.facebook.com/prolocopietrapertosa1/?locale=it_IT" target="_blank" rel="noopener noreferrer">Facebook</a>
          <a href="https://www.instagram.com/proloco_pietrapertosa/" target="_blank" rel="noopener noreferrer">Instagram</a>
          <a href="https://prolocopietrapertosa.org" target="_blank" rel="noopener noreferrer">{isEn ? "Our archive" : "Il nostro archivio"}</a>
        </div>
      </div>

      <div className="f-bot">
        <span>© 2026 Pro Loco Pietrapertosa</span>
        <span>
          <i style={{ color: "var(--gold-soft)" }}>{isEn ? "made with heart, at 1.088 meters" : "fatto con il cuore, a 1.088 metri"}</i>
        </span>
      </div>
    </footer>
  );
}