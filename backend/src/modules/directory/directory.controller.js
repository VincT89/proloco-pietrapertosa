const db = require('../../config/db');
const { cleanHtml } = require('../../utils/sanitizer');

exports.getDirectoryItems = async (req, res) => {
  try {
    const { category, lang } = req.query;
    let query = 'SELECT * FROM directory_items';
    let params = [];
    
    if (category) {
      query += ' WHERE category = ?';
      params.push(category);
    }
    
    query += ' ORDER BY sort_order ASC, created_at DESC';
    
    const [rows] = await db.query(query, params);
    
    const safeRows = rows.map(row => {
      let r = { ...row };
      if (lang === 'en') {
        r.title = r.title_en || r.title;
        r.subtitle = r.subtitle_en || r.subtitle;
        r.description = r.description_en || r.description;
        r.contact_info = r.contact_info_en || r.contact_info;
      }
      r.description = cleanHtml(r.description);
      r.description_en = cleanHtml(r.description_en || '');
      r.contact_info = cleanHtml(r.contact_info);
      r.contact_info_en = cleanHtml(r.contact_info_en || '');
      return r;
    });
    
    res.json({ items: safeRows });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Errore nel recupero della directory' });
  }
};

exports.getDirectoryItemById = async (req, res) => {
  try {
    const { id } = req.params;
    const { lang } = req.query;
    const [rows] = await db.query('SELECT * FROM directory_items WHERE id = ?', [id]);
    
    if (rows.length === 0) return res.status(404).json({ error: 'Elemento non trovato' });
    
    let safeItem = { ...rows[0] };
    if (lang === 'en') {
      safeItem.title = safeItem.title_en || safeItem.title;
      safeItem.subtitle = safeItem.subtitle_en || safeItem.subtitle;
      safeItem.description = safeItem.description_en || safeItem.description;
      safeItem.contact_info = safeItem.contact_info_en || safeItem.contact_info;
    }
    
    safeItem.description = cleanHtml(safeItem.description);
    safeItem.description_en = cleanHtml(safeItem.description_en || '');
    safeItem.contact_info = cleanHtml(safeItem.contact_info);
    safeItem.contact_info_en = cleanHtml(safeItem.contact_info_en || '');
    
    res.json({ item: safeItem });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Errore nel recupero dell\'elemento' });
  }
};

exports.createDirectoryItem = async (req, res) => {
  try {
    const { 
      category, 
      title, title_en, 
      subtitle, subtitle_en, 
      description, description_en, 
      contact_info, contact_info_en, 
      gallery, stats, sort_order 
    } = req.body;
    
    if (!title || !category) return res.status(400).json({ error: 'Titolo e categoria sono obbligatori' });

    const galleryJson = gallery ? JSON.stringify(gallery) : null;
    const statsJson = stats ? JSON.stringify(stats) : null;
    
    const safeDesc = cleanHtml(description || '');
    const safeDescEn = cleanHtml(description_en || '');
    const safeContact = cleanHtml(contact_info || '');
    const safeContactEn = cleanHtml(contact_info_en || '');

    const [result] = await db.query(
      `INSERT INTO directory_items 
      (category, title, title_en, subtitle, subtitle_en, description, description_en, contact_info, contact_info_en, gallery, stats, sort_order) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [category, title, title_en || null, subtitle || '', subtitle_en || null, safeDesc, safeDescEn, safeContact, safeContactEn, galleryJson, statsJson, sort_order || 0]
    );

    res.status(201).json({ message: 'Elemento creato con successo', id: result.insertId });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Errore durante la creazione dell\'elemento' });
  }
};

exports.updateDirectoryItem = async (req, res) => {
  try {
    const { id } = req.params;
    const { 
      category, 
      title, title_en, 
      subtitle, subtitle_en, 
      description, description_en, 
      contact_info, contact_info_en, 
      gallery, stats, sort_order 
    } = req.body;

    const galleryJson = gallery ? JSON.stringify(gallery) : null;
    const statsJson = stats ? JSON.stringify(stats) : null;
    
    const safeDesc = cleanHtml(description || '');
    const safeDescEn = cleanHtml(description_en || '');
    const safeContact = cleanHtml(contact_info || '');
    const safeContactEn = cleanHtml(contact_info_en || '');

    await db.query(
      `UPDATE directory_items 
       SET category = ?, title = ?, title_en = ?, subtitle = ?, subtitle_en = ?, description = ?, description_en = ?, contact_info = ?, contact_info_en = ?, gallery = ?, stats = ?, sort_order = ?
       WHERE id = ?`,
      [category, title, title_en || null, subtitle || '', subtitle_en || null, safeDesc, safeDescEn, safeContact, safeContactEn, galleryJson, statsJson, sort_order || 0, id]
    );

    res.json({ message: 'Elemento aggiornato con successo' });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Errore durante l\'aggiornamento dell\'elemento' });
  }
};

exports.deleteDirectoryItem = async (req, res) => {
  try {
    const { id } = req.params;
    await db.query('DELETE FROM directory_items WHERE id = ?', [id]);
    res.json({ message: 'Elemento eliminato con successo' });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Errore durante l\'eliminazione' });
  }
};
