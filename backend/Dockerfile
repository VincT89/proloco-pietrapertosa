FROM node:20-alpine
WORKDIR /app

# Installa le dipendenze
COPY package*.json ./
RUN npm ci --only=production

# Copia il codice sorgente
COPY . .

# Esponi la porta
EXPOSE 4000

# Avvia l'applicazione
CMD ["node", "src/app.js"]
