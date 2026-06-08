"use client";
import { useEffect } from 'react';
import { usePathname } from 'next/navigation';

export default function SEO() {
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
  const enToIt = Object.fromEntries(Object.entries(itToEn).map(([it, en]) => [en, it]));

  let rawPath = isEn ? (pathname.replace('/en', '') || '/') : pathname;
  const itPath = isEn ? (enToIt[rawPath] || rawPath) : rawPath;
  const enPath = `/en${itToEn[itPath] !== undefined ? itToEn[itPath] : itPath}`;
  
  // Assicurati di impostare il dominio corretto per la produzione
  const domain = process.env.NEXT_PUBLIC_SITE_URL || 'https://prolocopietrapertosa.it';
  const itUrl = `${domain}${itPath}`;
  const enUrl = `${domain}${enPath}`;

  useEffect(() => {
    document.documentElement.lang = isEn ? 'en' : 'it';
  }, [isEn]);

  return (
    <>
      <link rel="canonical" href={isEn ? enUrl : itUrl} />
      <link rel="alternate" hrefLang="it-IT" href={itUrl} />
      <link rel="alternate" hrefLang="en-US" href={enUrl} />
      <link rel="alternate" hrefLang="x-default" href={itUrl} />
    </>
  );
}
