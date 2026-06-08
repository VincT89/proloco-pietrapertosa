const rateLimit = require('express-rate-limit');

exports.loginLimiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minuti
  max: 5, // Blocca dopo 5 tentativi
  message: { error: 'Troppi tentativi di login. Riprova tra 15 minuti.' },
  standardHeaders: true,
  legacyHeaders: false,
});

exports.uploadLimiter = rateLimit({
  windowMs: 60 * 1000, // 1 minuto
  max: 10, // Max 10 upload al minuto
  message: { error: 'Troppi file caricati in poco tempo. Attendi un momento.' },
  standardHeaders: true,
  legacyHeaders: false,
});
