const db = require('../../config/db');
const slugify = require('../../utils/slugify');
const { cleanHtml } = require('../../utils/sanitizer');

exports.getAllEvents = async (req, res) => {
  try {
    const { upcoming, past, month, year, lang } = req.query;
    
    let query = `
      SELECT e.*, m.url as cover_image_url 
      FROM events e 
      LEFT JOIN media m ON e.cover_media_id = m.id 
    `;
    let conditions = [];
    let params = [];

    if (upcoming === 'true') {
      conditions.push('e.start_date >= CURDATE()');
    }
    
    if (past === 'true') {
      conditions.push('e.start_date < CURDATE()');
    }

    if (month && year) {
      conditions.push('MONTH(e.start_date) = ? AND YEAR(e.start_date) = ?');
      params.push(month, year);
    }

    if (conditions.length > 0) {
      query += ' WHERE ' + conditions.join(' AND ');
    }

    query += ' ORDER BY e.start_date ASC';

    const [rows] = await db.query(query, params);
    
    const safeRows = rows.map(row => {
      let r = { ...row };
      if (lang === 'en') {
        r.title = r.title_en || r.title;
        r.description = r.description_en || r.description;
        r.location = r.location_en || r.location;
        r.category = r.category_en || r.category;
      }
      r.description = cleanHtml(r.description);
      r.description_en = cleanHtml(r.description_en || '');
      return r;
    });
    
    res.json({ events: safeRows });
  } catch (error) {
    res.status(500).json({ error: 'Errore nel recupero degli eventi' });
  }
};

exports.getEventByIdOrSlug = async (req, res) => {
  try {
    const { id } = req.params;
    const { lang } = req.query;
    const isNumeric = !isNaN(id);
    const query = isNumeric 
      ? 'SELECT e.*, m.url as cover_image_url FROM events e LEFT JOIN media m ON e.cover_media_id = m.id WHERE e.id = ?'
      : 'SELECT e.*, m.url as cover_image_url FROM events e LEFT JOIN media m ON e.cover_media_id = m.id WHERE e.slug = ?';
      
    const [rows] = await db.query(query, [id]);
    if (rows.length === 0) return res.status(404).json({ error: 'Evento non trovato' });
    
    let safeEvent = { ...rows[0] };
    if (lang === 'en') {
      safeEvent.title = safeEvent.title_en || safeEvent.title;
      safeEvent.description = safeEvent.description_en || safeEvent.description;
      safeEvent.location = safeEvent.location_en || safeEvent.location;
      safeEvent.category = safeEvent.category_en || safeEvent.category;
    }
    
    safeEvent.description = cleanHtml(safeEvent.description);
    safeEvent.description_en = cleanHtml(safeEvent.description_en || '');
    
    res.json({ event: safeEvent });
  } catch (error) {
    res.status(500).json({ error: 'Errore nel recupero dell\'evento' });
  }
};

exports.createEvent = async (req, res) => {
  try {
    const { 
      title, title_en, 
      description, description_en, 
      start_date, end_date, 
      location, location_en, 
      category, category_en, 
      cover_media_id, status, gallery 
    } = req.body;
    
    if (!title || !start_date) return res.status(400).json({ error: 'Titolo e data di inizio sono obbligatori' });

    let slug = slugify(title);
    
    const [existing] = await db.query('SELECT id FROM events WHERE slug = ?', [slug]);
    if (existing.length > 0) slug = `${slug}-${Date.now()}`;

    const galleryJson = gallery ? JSON.stringify(gallery) : null;
    
    const safeDesc = cleanHtml(description || '');
    const safeDescEn = cleanHtml(description_en || '');

    const [result] = await db.query(
      'INSERT INTO events (title, title_en, slug, description, description_en, start_date, end_date, location, location_en, category, category_en, cover_media_id, status, gallery) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
      [title, title_en || null, slug, safeDesc, safeDescEn, start_date, end_date || null, location || '', location_en || null, category || '', category_en || null, cover_media_id || null, status || 'draft', galleryJson]
    );

    res.status(201).json({ message: 'Evento creato con successo', id: result.insertId, slug });
  } catch (error) {
    res.status(500).json({ error: "Errore nella creazione dell'evento" });
  }
};

exports.updateEvent = async (req, res) => {
  try {
    const { id } = req.params;
    const { 
      title, title_en, 
      description, description_en, 
      start_date, end_date, 
      location, location_en, 
      category, category_en, 
      cover_media_id, status, gallery 
    } = req.body;

    const galleryJson = gallery ? JSON.stringify(gallery) : null;
    
    const safeDesc = cleanHtml(description || '');
    const safeDescEn = cleanHtml(description_en || '');

    let updateQuery = 'UPDATE events SET title_en = ?, description = ?, description_en = ?, start_date = ?, end_date = ?, location = ?, location_en = ?, category = ?, category_en = ?, cover_media_id = ?, status = ?, gallery = ?';
    let params = [title_en || null, safeDesc, safeDescEn, start_date, end_date || null, location || '', location_en || null, category || '', category_en || null, cover_media_id || null, status || 'draft', galleryJson];

    if (title) {
      updateQuery += ', title = ?, slug = ?';
      let slug = slugify(title);
      const [existing] = await db.query('SELECT id FROM events WHERE slug = ? AND id != ?', [slug, id]);
      if (existing.length > 0) slug = `${slug}-${Date.now()}`;
      params.push(title, slug);
    }

    updateQuery += ' WHERE id = ?';
    params.push(id);

    const [result] = await db.query(updateQuery, params);
    if (result.affectedRows === 0) return res.status(404).json({ error: 'Evento non trovato' });

    res.json({ message: 'Evento aggiornato con successo' });
  } catch (error) {
    res.status(500).json({ error: "Errore nell'aggiornamento dell'evento" });
  }
};

exports.deleteEvent = async (req, res) => {
  try {
    const { id } = req.params;
    const [result] = await db.query('DELETE FROM events WHERE id = ?', [id]);
    if (result.affectedRows === 0) return res.status(404).json({ error: 'Evento non trovato' });
    res.json({ message: 'Evento eliminato con successo' });
  } catch (error) {
    res.status(500).json({ error: "Errore nell'eliminazione dell'evento" });
  }
};
