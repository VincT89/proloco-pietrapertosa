const express = require('express');
const router = express.Router();
const directoryController = require('./directory.controller');
const { requireAdmin } = require('../../middleware/auth');
const validate = require('../../middleware/validator');

router.get('/', directoryController.getDirectoryItems);
router.get('/:id', directoryController.getDirectoryItemById);
router.post('/', requireAdmin, validate('directory'), directoryController.createDirectoryItem);
router.put('/:id', requireAdmin, validate('directory'), directoryController.updateDirectoryItem);
router.delete('/:id', requireAdmin, directoryController.deleteDirectoryItem);

module.exports = router;
