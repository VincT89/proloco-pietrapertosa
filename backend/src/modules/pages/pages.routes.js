const express = require('express');
const router = express.Router();
const pagesController = require('./pages.controller');
const { requireAdmin } = require('../../middleware/auth');
const validate = require('../../middleware/validator');

router.get('/:slug', pagesController.getPageSettings);
router.put('/:slug', requireAdmin, validate('page'), pagesController.updatePageSettings);

module.exports = router;
