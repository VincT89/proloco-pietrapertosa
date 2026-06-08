const multer = require('multer');
const fs = require('fs');
const path = require('path');

const uploadDir = path.join(__dirname, '../../../uploads');

// Crea la cartella se non esiste
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

// Usiamo il disk storage per evitare di saturare la RAM
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, uploadDir);
  },
  filename: function (req, file, cb) {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    cb(null, file.fieldname + '-' + uniqueSuffix + '-' + file.originalname);
  }
});

// Rimossa la doppia logica (fileFilter): l'accettazione finale è delegata al controller
const upload = multer({
  storage,
  limits: {
    fileSize: 50 * 1024 * 1024 // Limite 50 MB (i controlli stretti per foto/video sono nel controller)
  }
});

module.exports = upload;
