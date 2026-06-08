const db = require('../../config/db');

exports.getAll = async (req, res) => {
  try {
    const [rows] = await db.query('SELECT * FROM gallery_albums ORDER BY section_date DESC, created_at DESC');
    // Ensure media_urls is parsed if it's a string
    const items = rows.map(row => {
      let urls = [];
      if (typeof row.media_urls === 'string') {
        try { urls = JSON.parse(row.media_urls); } catch (e) {}
      } else if (Array.isArray(row.media_urls)) {
        urls = row.media_urls;
      }
      return { ...row, media_urls: urls };
    });
    res.json({ items });
  } catch (error) {
    console.error('Error fetching gallery albums:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
};

exports.create = async (req, res) => {
  try {
    const { title, title_en, media_urls, section_date, sort_order } = req.body;
    const urlsJson = JSON.stringify(media_urls || []);
    const [result] = await db.query(
      'INSERT INTO gallery_albums (title, title_en, media_urls, section_date, sort_order) VALUES (?, ?, ?, ?, ?)',
      [title || null, title_en || null, urlsJson, section_date || null, sort_order || 0]
    );
    res.status(201).json({ message: 'Gallery album created', id: result.insertId });
  } catch (error) {
    console.error('Error creating gallery album:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
};

exports.update = async (req, res) => {
  try {
    const { id } = req.params;
    const { title, title_en, media_urls, section_date, sort_order } = req.body;
    const urlsJson = JSON.stringify(media_urls || []);
    await db.query(
      'UPDATE gallery_albums SET title=?, title_en=?, media_urls=?, section_date=?, sort_order=? WHERE id=?',
      [title || null, title_en || null, urlsJson, section_date || null, sort_order || 0, id]
    );
    res.json({ message: 'Gallery album updated' });
  } catch (error) {
    console.error('Error updating gallery album:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
};

exports.delete = async (req, res) => {
  try {
    const { id } = req.params;
    await db.query('DELETE FROM gallery_albums WHERE id=?', [id]);
    res.json({ message: 'Gallery album deleted' });
  } catch (error) {
    console.error('Error deleting gallery album:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
};
