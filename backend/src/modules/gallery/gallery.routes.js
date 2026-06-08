const express = require('express');
const router = express.Router();
const galleryController = require('./gallery.controller');
const { verifyToken } = require('../../middleware/auth');

// Public routes
router.get('/', galleryController.getAll);

// Protected routes
router.post('/', verifyToken, galleryController.create);
router.put('/:id', verifyToken, galleryController.update);
router.delete('/:id', verifyToken, galleryController.delete);

module.exports = router;
