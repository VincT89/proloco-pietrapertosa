"use client";
import Link from "next/link";
import { usePathname, useSearchParams } from "next/navigation";
import { Lock } from "lucide-react";
import { getLang, withLang } from "../utils/lang";

export default function Footer() {
  const pathname = usePathname() || '';
  const searchParams = useSearchParams();
  const currentLang = getLang(searchParams);
  const isEn = currentLang === "en";

  const getRoute = (path) => {
    return withLang(path, currentLang);
  };

  return (
    <footer>
      <div className="f-big" id="fBig">
        Pietra<em>pertosa</em>
      </div>

      <div className="f-cols">
        <div className="fb footer-brand-box">
          <img src="/images/logo.png" alt="Pro Loco Pietrapertosa Logo" className="footer-logo" />
          <div>
            <b>Pro Loco Pietrapertosana</b>
            <ul className="fu">
              <li><a href="mailto:prolocopietrapertosa@gmail.com">prolocopietrapertosa@gmail.com</a></li>
              <li>342 989 6770</li>
            </ul>
            <p>
              {isEn ? "Non-profit association for the touristic and cultural promotion of the village, in the Park of the Lucanian Dolomites." : "Associazione no profit per la promozione turistica e culturale del borgo, nel Parco delle Piccole Dolomiti Lucane."}
            </p>
            <div className="footer-address">
              Via della Speranza, 159<br/>
              85010 Pietrapertosa (PZ)<br/>
              P.Iva: 01925320762<br/>
              Cod. Fiscale: 96028030763
            </div>
            
            <div className="footer-admin-link-wrapper">
              <Link href="/admin/login" className="adm-link footer-admin-link">
                <Lock size={12} className="footer-admin-icon" />
                {isEn ? "Restricted Access" : "Accesso Riservato"}
              </Link>
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
          <a href="https://www.parcogallipolicognato.it/comuni_dettaglio.php?id=76061" target="_blank" rel="noopener noreferrer">Parco Gallipoli Cognato</a>
          <a href="https://www.basilicataturistica.it/territori/pietrapertosa/" target="_blank" rel="noopener noreferrer">APT Basilicata</a>
          <a href="https://borghipiubelliditalia.it/borgo/pietrapertosa/" target="_blank" rel="noopener noreferrer">Borghi più belli d&apos;Italia</a>
        </div>

        <div>
          <h5>{isEn ? "Follow us" : "Seguici"}</h5>
          <a href="https://www.facebook.com/prolocopietrapertosa1/?locale=it_IT" target="_blank" rel="noopener noreferrer">Facebook</a>
          <a href="https://www.instagram.com/proloco_pietrapertosa/" target="_blank" rel="noopener noreferrer">Instagram</a>
          <Link href={getRoute("/galleria")}>Il nostro archivio</Link>
        </div>
      </div>

      <div className="f-bot">
        <span>© 2026 Pro Loco Pietrapertosa</span>
        <span>
          <i className="footer-credit">{isEn ? "made with heart, at 1.088 meters" : "fatto con il cuore, a 1.088 metri"}</i>
        </span>
      </div>
    </footer>
  );
}