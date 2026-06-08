const jwt = require('jsonwebtoken');

const verifyToken = (req, res, next) => {
  const token = req.headers['authorization'];
  if (!token) return res.status(403).json({ error: 'Nessun token fornito. Accesso negato.' });

  try {
    const bearer = token.split(' ')[1]; // Expecting "Bearer <token>"
    const decoded = jwt.verify(bearer, process.env.JWT_SECRET);
    req.user = decoded;
    next();
  } catch (error) {
    return res.status(401).json({ error: 'Token non valido o scaduto.' });
  }
};

const requireAdmin = (req, res, next) => {
  verifyToken(req, res, () => {
    if (req.user && req.user.role === 'admin') {
      next();
    } else {
      return res.status(403).json({ error: 'Accesso negato. Sono richiesti i privilegi di amministratore.' });
    }
  });
};

module.exports = { verifyToken, requireAdmin };
