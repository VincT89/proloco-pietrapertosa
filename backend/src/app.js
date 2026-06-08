require('dotenv').config();
const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const errorHandler = require('./middleware/errorHandler');

const app = express();
const PORT = process.env.PORT || 4000;

// Middleware
app.use(helmet());

const allowedOrigins = [
  'http://localhost:3000',
  'http://localhost:3001',
  process.env.FRONTEND_URL,
  process.env.ADMIN_URL
].filter(Boolean);

app.use(cors({
  origin: function (origin, callback) {
    if (!origin || allowedOrigins.includes(origin)) {
      callback(null, true);
    } else {
      callback(new Error('CORS non consentito per questo dominio'));
    }
  },
  credentials: true
}));

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Basic Health Check Route
app.get('/api/health', (req, res) => {
  res.json({ status: 'ok', message: 'Backend Pro Loco Pietrapertosa is running!' });
});

// Import Routes
app.use('/api/auth', require('./modules/auth/auth.routes'));
app.use('/api/media', require('./modules/media/media.routes'));
app.use('/api/events', require('./modules/events/events.routes'));
app.use('/api/news', require('./modules/news/news.routes'));
app.use('/api/pages', require('./modules/pages/pages.routes'));
app.use('/api/directory', require('./modules/directory/directory.routes'));
app.use('/api/gallery', require('./modules/gallery/gallery.routes'));

// Error Handling Middleware
app.use(errorHandler);

// Start Server
app.listen(PORT, () => {
  console.log(`Server listening on http://localhost:${PORT}`);
});
