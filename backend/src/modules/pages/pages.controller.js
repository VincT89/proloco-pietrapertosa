const db = require('../../config/db');
const { cleanHtml } = require('../../utils/sanitizer');

exports.getPageSettings = async (req, res) => {
  try {
    const { slug } = req.params;
    const lang = req.query.lang === 'en' ? 'en' : 'it';
    const [rows] = await db.query('SELECT * FROM page_settings WHERE page_slug = ?', [slug]);
    
    if (rows.length === 0) {
      return res.status(404).json({ error: 'Pagina non trovata' });
    }
    
    const row = rows[0];
    
    let safePage = { ...row };
    
    // Fallback logic
    if (lang === 'en') {
      safePage.hero_title = row.hero_title_en || row.hero_title;
      safePage.hero_subtitle = row.hero_subtitle_en || row.hero_subtitle;
      safePage.intro_title = row.intro_title_en || row.intro_title;
      safePage.intro_text = row.intro_text_en || row.intro_text;
    }

    // Applica sanitizzazione sia in italiano (se è it) sia in inglese (se è en o se stiamo restituendo tutti i dati in admin non gestiti qui, ma per il frontend diamo solo intro_text pulito)
    // NB: admin chiederà i dati senza `lang` o li gestisce l'admin diversamente? L'admin ha bisogno di TUTTI i dati. Se lang=en, sostituiamo.
    safePage.intro_text = cleanHtml(safePage.intro_text);
    safePage.intro_text_en = cleanHtml(safePage.intro_text_en || ''); // Tienilo pulito
    
    res.json({ page: safePage });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Errore nel recupero della pagina' });
  }
};

exports.updatePageSettings = async (req, res) => {
  try {
    const { slug } = req.params;
    const { 
      hero_title, hero_title_en,
      hero_subtitle, hero_subtitle_en,
      hero_image_url, 
      intro_title, intro_title_en,
      intro_text, intro_text_en 
    } = req.body;
    
    const safeIntro = cleanHtml(intro_text || '');
    const safeIntroEn = cleanHtml(intro_text_en || '');

    await db.query(
      `UPDATE page_settings 
       SET hero_title = ?, hero_title_en = ?, 
           hero_subtitle = ?, hero_subtitle_en = ?, 
           hero_image_url = ?, 
           intro_title = ?, intro_title_en = ?, 
           intro_text = ?, intro_text_en = ? 
       WHERE page_slug = ?`,
      [
        hero_title, hero_title_en, 
        hero_subtitle, hero_subtitle_en, 
        hero_image_url, 
        intro_title, intro_title_en, 
        safeIntro, safeIntroEn, 
        slug
      ]
    );

    res.json({ message: 'Pagina aggiornata con successo' });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Errore durante l\'aggiornamento della pagina' });
  }
};
