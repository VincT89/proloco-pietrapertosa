const db = require('../../config/db');
const slugify = require('../../utils/slugify');
const { cleanHtml } = require('../../utils/sanitizer');

exports.getAllNews = async (req, res) => {
  try {
    const lang = req.query.lang;
    const [rows] = await db.query(`
      SELECT n.*, m.url as cover_image_url 
      FROM news n 
      LEFT JOIN media m ON n.cover_media_id = m.id 
      ORDER BY n.created_at DESC
    `);
    
    const safeRows = rows.map(row => {
      let r = { ...row };
      if (lang === 'en') {
        r.title = r.title_en || r.title;
        r.excerpt = r.excerpt_en || r.excerpt;
        r.content = r.content_en || r.content;
      }
      r.excerpt = cleanHtml(r.excerpt);
      r.excerpt_en = cleanHtml(r.excerpt_en || '');
      r.content = cleanHtml(r.content);
      r.content_en = cleanHtml(r.content_en || '');
      return r;
    });
    res.json({ news: safeRows });
  } catch (error) {
    res.status(500).json({ error: 'Errore nel recupero delle notizie' });
  }
};

exports.getNewsByIdOrSlug = async (req, res) => {
  try {
    const { id } = req.params;
    const lang = req.query.lang;
    const isNumeric = !isNaN(id);
    const query = isNumeric 
      ? 'SELECT n.*, m.url as cover_image_url FROM news n LEFT JOIN media m ON n.cover_media_id = m.id WHERE n.id = ?'
      : 'SELECT n.*, m.url as cover_image_url FROM news n LEFT JOIN media m ON n.cover_media_id = m.id WHERE n.slug = ?';
      
    const [rows] = await db.query(query, [id]);
    if (rows.length === 0) return res.status(404).json({ error: 'Notizia non trovata' });
    
    let safeNews = { ...rows[0] };
    if (lang === 'en') {
      safeNews.title = safeNews.title_en || safeNews.title;
      safeNews.excerpt = safeNews.excerpt_en || safeNews.excerpt;
      safeNews.content = safeNews.content_en || safeNews.content;
    }
    
    safeNews.excerpt = cleanHtml(safeNews.excerpt);
    safeNews.excerpt_en = cleanHtml(safeNews.excerpt_en || '');
    safeNews.content = cleanHtml(safeNews.content);
    safeNews.content_en = cleanHtml(safeNews.content_en || '');
    
    res.json({ news: safeNews });
  } catch (error) {
    res.status(500).json({ error: 'Errore nel recupero della notizia' });
  }
};

exports.createNews = async (req, res) => {
  try {
    const { title, title_en, excerpt, excerpt_en, content, content_en, cover_media_id, status, published_at, gallery } = req.body;
    if (!title) return res.status(400).json({ error: 'Il titolo è obbligatorio' });

    let slug = slugify(title);
    
    const [existing] = await db.query('SELECT id FROM news WHERE slug = ?', [slug]);
    if (existing.length > 0) {
      slug = `${slug}-${Date.now()}`;
    }

    const galleryJson = gallery ? JSON.stringify(gallery) : null;
    
    const safeExcerpt = cleanHtml(excerpt || '');
    const safeExcerptEn = cleanHtml(excerpt_en || '');
    const safeContent = cleanHtml(content || '');
    const safeContentEn = cleanHtml(content_en || '');

    const [result] = await db.query(
      'INSERT INTO news (title, title_en, slug, excerpt, excerpt_en, content, content_en, cover_media_id, status, published_at, gallery) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
      [title, title_en || null, slug, safeExcerpt, safeExcerptEn, safeContent, safeContentEn, cover_media_id || null, status || 'draft', published_at || null, galleryJson]
    );

    res.status(201).json({ message: 'Notizia creata con successo', id: result.insertId, slug });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Errore nella creazione della notizia' });
  }
};

exports.updateNews = async (req, res) => {
  try {
    const { id } = req.params;
    const { title, title_en, excerpt, excerpt_en, content, content_en, cover_media_id, status, published_at, gallery } = req.body;

    const galleryJson = gallery ? JSON.stringify(gallery) : null;
    
    const safeExcerpt = cleanHtml(excerpt || '');
    const safeExcerptEn = cleanHtml(excerpt_en || '');
    const safeContent = cleanHtml(content || '');
    const safeContentEn = cleanHtml(content_en || '');

    let updateQuery = 'UPDATE news SET title_en = ?, excerpt = ?, excerpt_en = ?, content = ?, content_en = ?, cover_media_id = ?, status = ?, published_at = ?, gallery = ?';
    let params = [title_en || null, safeExcerpt, safeExcerptEn, safeContent, safeContentEn, cover_media_id || null, status, published_at || null, galleryJson];

    if (title) {
      updateQuery += ', title = ?, slug = ?';
      let slug = slugify(title);
      const [existing] = await db.query('SELECT id FROM news WHERE slug = ? AND id != ?', [slug, id]);
      if (existing.length > 0) slug = `${slug}-${Date.now()}`;
      params.push(title, slug);
    }

    updateQuery += ' WHERE id = ?';
    params.push(id);

    const [result] = await db.query(updateQuery, params);
    if (result.affectedRows === 0) return res.status(404).json({ error: 'Notizia non trovata' });

    res.json({ message: 'Notizia aggiornata con successo' });
  } catch (error) {
    res.status(500).json({ error: "Errore nell'aggiornamento della notizia" });
  }
};

exports.deleteNews = async (req, res) => {
  try {
    const { id } = req.params;
    const [result] = await db.query('DELETE FROM news WHERE id = ?', [id]);
    if (result.affectedRows === 0) return res.status(404).json({ error: 'Notizia non trovata' });
    res.json({ message: 'Notizia eliminata con successo' });
  } catch (error) {
    res.status(500).json({ error: "Errore nell'eliminazione della notizia" });
  }
};
