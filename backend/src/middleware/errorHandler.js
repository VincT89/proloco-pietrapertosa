// Error handler centralizzato per l'intera applicazione
const errorHandler = (err, req, res, next) => {
  // Log dell'errore (in un sistema enterprise questo verrebbe mandato a Datadog/Sentry)
  console.error('[Error Handler]', {
    message: err.message,
    stack: process.env.NODE_ENV === 'production' ? '🥞' : err.stack,
    path: req.path,
    method: req.method,
    body: req.body
  });

  // Gestione specifica degli errori noti (es. Multer)
  if (err.name === 'MulterError') {
    return res.status(400).json({
      error: 'Errore di caricamento file',
      details: err.message
    });
  }

  // Errore generico (Internal Server Error)
  // In produzione non sveliamo la callstack o messaggi interni del DB
  const statusCode = err.status || 500;
  const errorResponse = {
    error: 'Si è verificato un errore imprevisto sul server.',
    ...(process.env.NODE_ENV !== 'production' && { details: err.message })
  };

  res.status(statusCode).json(errorResponse);
};

module.exports = errorHandler;
