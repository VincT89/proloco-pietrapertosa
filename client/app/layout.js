import './globals.css';
import { Cormorant_Garamond, Source_Sans_3 } from 'next/font/google';

const cormorant = Cormorant_Garamond({
  subsets: ['latin'],
  weight: ['300', '400', '500', '600', '700'],
  style: ['normal', 'italic'],
  variable: '--font-cormorant',
  display: 'swap',
});

const sourceSans = Source_Sans_3({
  subsets: ['latin'],
  weight: ['300', '400', '500', '600'],
  style: ['normal', 'italic'],
  variable: '--font-source-sans',
  display: 'swap',
});

export const metadata = {
  title: 'Pietrapertosa — Sospesi tra cielo e pietra · Pro Loco',
  description: 'Associazione Pro Loco Pietrapertosana: il borgo più alto della Basilicata, tra le guglie delle Dolomiti Lucane. Castello saraceno, Arabata, Volo dell\'Angelo, news, eventi, locandine e foto.',
};

import SEO from '@/components/SEO';

export default function RootLayout({ children }) {
  return (
    <html lang="it" className={`${cormorant.variable} ${sourceSans.variable}`}>
      <body className="ready">
        <SEO />
        {children}
      </body>
    </html>
  );
}
