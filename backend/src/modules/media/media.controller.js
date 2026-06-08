const cloudinary = require('../../config/cloudinary');
const db = require('../../config/db');
const FileType = require('file-type');
const fs = require('fs');

exports.uploadMedia = async (req, res) => {
  try {
    if (!req.file) {
      return res.status(400).json({ error: 'Nessun file fornito.' });
    }

    try {
      // 1. Verifica firma file-type leggendo il path sul disco
      const typeInfo = await FileType.fromFile(req.file.path);
      if (!typeInfo) {
        return res.status(400).json({ error: 'Formato file sconosciuto o file corrotto.' });
      }
      
      const isImage = typeInfo.mime.startsWith('image/');
      const isVideo = typeInfo.mime.startsWith('video/');

      if (!isImage && !isVideo) {
        return res.status(400).json({ error: 'Tipo di file non consentito. Solo immagini e video sono permessi.' });
      }

      // 2. Controllo dimensione specifico leggendo la size dal disco
      const stats = fs.statSync(req.file.path);
      const sizeMB = stats.size / (1024 * 1024);
      if (isImage && sizeMB > 5) {
        return res.status(400).json({ error: "L'immagine supera il limite di 5MB." });
      }
      if (isVideo && sizeMB > 50) {
        return res.status(400).json({ error: 'Il video supera il limite di 50MB.' });
      }

      const { folder = 'general', alt = '', caption = '' } = req.body;

      // 3. Carica su Cloudinary direttamente dal path
      const result = await cloudinary.uploader.upload(req.file.path, {
        folder: `proloco/${folder}`,
        resource_type: 'auto'
      });

      // 4. Salva nel database
      const [insertResult] = await db.query(
        'INSERT INTO media (public_id, url, resource_type, alt, caption, folder) VALUES (?, ?, ?, ?, ?, ?)',
        [result.public_id, result.secure_url, result.resource_type, alt, caption, folder]
      );

      res.status(201).json({
        message: 'File caricato con successo',
        media: {
          id: insertResult.insertId,
          public_id: result.public_id,
          url: result.secure_url,
          alt,
          caption,
          folder
        }
      });
    } finally {
      // 5. Cleanup garantito: cancella SEMPRE il file dal disco per non intasare il VPS
      if (fs.existsSync(req.file.path)) {
        fs.unlinkSync(req.file.path);
      }
    }
  } catch (error) {
    console.error('Errore Upload Media:', error);
    res.status(500).json({ error: 'Errore interno durante il caricamento del media.' });
  }
};

exports.getAllMedia = async (req, res) => {
  try {
    const { folder } = req.query;
    let query = 'SELECT * FROM media ORDER BY created_at DESC';
    let params = [];

    if (folder) {
      query = 'SELECT * FROM media WHERE folder = ? ORDER BY created_at DESC';
      params = [folder];
    }

    const [rows] = await db.query(query, params);
    res.json({ media: rows });
  } catch (error) {
    console.error('Errore Get Media:', error);
    res.status(500).json({ error: 'Errore durante il recupero dei media.' });
  }
};

exports.deleteMedia = async (req, res) => {
  try {
    const { id } = req.params;

    // Recupera public_id per eliminare da Cloudinary
    const [rows] = await db.query('SELECT public_id FROM media WHERE id = ?', [id]);
    if (rows.length === 0) return res.status(404).json({ error: 'Media non trovato.' });

    const publicId = rows[0].public_id;

    // 1. Elimina da Cloudinary
    await cloudinary.uploader.destroy(publicId);

    // 2. Elimina dal database
    await db.query('DELETE FROM media WHERE id = ?', [id]);

    res.json({ message: 'Media eliminato con successo.' });
  } catch (error) {
    console.error('Errore Delete Media:', error);
    res.status(500).json({ error: 'Errore durante l\'eliminazione del media.' });
  }
};
