const express = require('express');
const router = express.Router();
const newsController = require('./news.controller');
const { requireAdmin } = require('../../middleware/auth');
const validate = require('../../middleware/validator');

// Rotte pubbliche
router.get('/', newsController.getAllNews);
router.get('/:id', newsController.getNewsByIdOrSlug);

// Rotte protette (Admin)
router.post('/', requireAdmin, validate('news'), newsController.createNews);
router.put('/:id', requireAdmin, validate('news'), newsController.updateNews);
router.delete('/:id', requireAdmin, newsController.deleteNews);

module.exports = router;
