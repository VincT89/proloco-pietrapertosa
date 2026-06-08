const express = require('express');
const router = express.Router();
const eventsController = require('./events.controller');
const { requireAdmin } = require('../../middleware/auth');
const validate = require('../../middleware/validator');

// Rotte pubbliche
router.get('/', eventsController.getAllEvents);
router.get('/:id', eventsController.getEventByIdOrSlug);

// Rotte protette (Admin)
router.post('/', requireAdmin, validate('event'), eventsController.createEvent);
router.put('/:id', requireAdmin, validate('event'), eventsController.updateEvent);
router.delete('/:id', requireAdmin, eventsController.deleteEvent);

module.exports = router;
