import './globals.css';

export const metadata = {
  title: 'Pietrapertosa — Sospesi tra cielo e pietra · Pro Loco',
  description: 'Associazione Pro Loco Pietrapertosana: il borgo più alto della Basilicata, tra le guglie delle Dolomiti Lucane. Castello saraceno, Arabata, Volo dell\'Angelo, news, eventi, locandine e foto.',
};

export default function RootLayout({ children }) {
  return (
    <html lang="it">
      <body className="ready">{children}</body>
    </html>
  );
}
