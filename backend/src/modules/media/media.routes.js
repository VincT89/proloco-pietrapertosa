const express = require('express');
const router = express.Router();
const mediaController = require('./media.controller');
const upload = require('../../middleware/upload');
const { requireAdmin } = require('../../middleware/auth');
const { uploadLimiter } = require('../../middleware/rateLimiter');

router.get('/', mediaController.getAllMedia);
router.post('/upload', requireAdmin, uploadLimiter, upload.single('file'), mediaController.uploadMedia);
router.delete('/:id', requireAdmin, mediaController.deleteMedia);

module.exports = router;
