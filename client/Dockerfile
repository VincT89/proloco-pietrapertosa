FROM node:20-alpine AS builder
WORKDIR /app

# Installa le dipendenze
COPY package*.json ./
RUN npm ci

# Copia il sorgente e builda
COPY . .
RUN npm run build

# Stage di produzione
FROM node:20-alpine AS runner
WORKDIR /app
ENV NODE_ENV production

COPY --from=builder /app/public ./public
COPY --from=builder /app/.next/standalone ./
COPY --from=builder /app/.next/static ./.next/static

EXPOSE 3000

CMD ["node", "server.js"]
