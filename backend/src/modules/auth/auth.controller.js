const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const db = require('../../config/db');

// POST /api/auth/login
exports.login = async (req, res) => {
  try {
    const { email, password } = req.body;
    
    if (!email || !password) {
      return res.status(400).json({ error: 'Email e password obbligatorie' });
    }

    const [rows] = await db.query('SELECT * FROM users WHERE email = ?', [email]);
    if (rows.length === 0) {
      return res.status(401).json({ error: 'Credenziali non valide' });
    }

    const user = rows[0];
    const isMatch = await bcrypt.compare(password, user.password_hash);
    
    if (!isMatch) {
      return res.status(401).json({ error: 'Credenziali non valide' });
    }

    // Generate JWT
    const token = jwt.sign(
      { id: user.id, email: user.email, role: user.role },
      process.env.JWT_SECRET,
      { expiresIn: process.env.JWT_EXPIRES_IN || '7d' }
    );

    res.json({
      message: 'Login effettuato con successo',
      token,
      user: {
        id: user.id,
        name: user.name,
        email: user.email,
        role: user.role
      }
    });

  } catch (error) {
    console.error('Errore Login:', error);
    res.status(500).json({ error: 'Errore interno del server' });
  }
};

// GET /api/auth/me
exports.getMe = async (req, res) => {
  try {
    const [rows] = await db.query('SELECT id, name, email, role, created_at FROM users WHERE id = ?', [req.user.id]);
    if (rows.length === 0) return res.status(404).json({ error: 'Utente non trovato' });
    
    res.json({ user: rows[0] });
  } catch (error) {
    res.status(500).json({ error: 'Errore interno del server' });
  }
};

// PUT /api/auth/settings
exports.updateSettings = async (req, res) => {
  try {
    const { currentPassword, newEmail, newPassword } = req.body;
    
    if (!currentPassword) {
      return res.status(400).json({ error: 'La password attuale è obbligatoria per effettuare modifiche' });
    }

    const [rows] = await db.query('SELECT * FROM users WHERE id = ?', [req.user.id]);
    if (rows.length === 0) {
      return res.status(404).json({ error: 'Utente non trovato' });
    }

    const user = rows[0];
    const isMatch = await bcrypt.compare(currentPassword, user.password_hash);
    if (!isMatch) {
      return res.status(401).json({ error: 'La password attuale non è corretta' });
    }

    let updates = [];
    let values = [];

    if (newEmail && newEmail !== user.email) {
      updates.push('email = ?');
      values.push(newEmail);
    }

    if (newPassword) {
      const salt = await bcrypt.genSalt(10);
      const hashedPassword = await bcrypt.hash(newPassword, salt);
      updates.push('password_hash = ?');
      values.push(hashedPassword);
    }

    if (updates.length > 0) {
      values.push(req.user.id);
      await db.query(`UPDATE users SET ${updates.join(', ')} WHERE id = ?`, values);
    }

    res.json({ message: 'Impostazioni aggiornate con successo' });
  } catch (error) {
    console.error('Errore Update Settings:', error);
    res.status(500).json({ error: 'Errore interno del server' });
  }
};
