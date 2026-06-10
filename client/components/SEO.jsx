"use client";
import { useEffect } from 'react';
import { usePathname, useSearchParams } from 'next/navigation';
import { getLang, withLang } from '../utils/lang';

export default function SEO() {
  const pathname = usePathname() || '';
  const searchParams = useSearchParams();
  const currentLang = getLang(searchParams);
  const isEn = currentLang === 'en';
  
  // Assicurati di impostare il dominio corretto per la produzione
  const domain = process.env.NEXT_PUBLIC_SITE_URL || 'https://prolocopietrapertosa.it';
  const itUrl = `${domain}${withLang(pathname, 'it')}`;
  const enUrl = `${domain}${withLang(pathname, 'en')}`;

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
