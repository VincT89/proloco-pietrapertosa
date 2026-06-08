# Pro Loco Pietrapertosa - Web Platform

Piattaforma web ufficiale per la Pro Loco di Pietrapertosa. Include un frontend pubblico (Next.js) e un backend API protetto (Node.js/Express) con gestione contenuti tramite Pannello Admin.

## Struttura del Progetto
- `/client`: Frontend sviluppato in Next.js (App Router). Contiene le pagine pubbliche e il Pannello Admin.
- `/backend`: Backend Node.js/Express. Fornisce le API RESTful, gestione DB MySQL e integrazione Cloudinary per media.

## Sicurezza e Produzione
Il sistema è **Production Ready** e include:
1. **XSS Protection**: HTML rigorosamente sanificato lato server (`sanitize-html`).
2. **Rate Limiting**: Prevenzione Brute Force su Login e Upload Media.
3. **Admin Guard Centralizzato**: Rotte Admin inaccessibili senza token JWT valido.
4. **Validazione File**: Analisi firme file (`file-type`) e limiti dimensionali (5MB foto, 50MB video).
5. **CORS & Helmet**: Whitelisting dei domini autorizzati e header di sicurezza HTTP.

## Deploy Rapido (Docker)
1. Rinomina `backend/.env.example` in `backend/.env` e compila le variabili.
2. Esegui `docker-compose up -d --build` dalla root del progetto.
3. Il DB si autoconfigurerà, il Backend risponderà su `localhost:4000` e il Client su `localhost:3000`.
