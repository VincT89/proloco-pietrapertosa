const express = require('express');
const router = express.Router();
const authController = require('./auth.controller');
const { verifyToken } = require('../../middleware/auth');
const { loginLimiter } = require('../../middleware/rateLimiter');

router.post('/login', loginLimiter, authController.login);
router.get('/me', verifyToken, authController.getMe);
router.put('/settings', verifyToken, authController.updateSettings);
router.post('/logout', (req, res) => {
  // Con JWT il logout avviene cancellando il token dal client
  res.json({ message: 'Logout effettuato con successo' });
});

module.exports = router;
