const Joi = require('joi');

// Helper per gestire la gallery (che può arrivare come array o come stringa JSON)
const gallerySchema = Joi.alternatives().try(
  Joi.array().items(Joi.string()),
  Joi.string().custom((value, helpers) => {
    try {
      const parsed = JSON.parse(value);
      if (!Array.isArray(parsed)) return helpers.error('any.invalid');
      return parsed; // Restituisce l'array parsato
    } catch (err) {
      return helpers.error('any.invalid');
    }
  })
).default([]);

// Schemi di validazione
const schemas = {
  news: Joi.object({
    title: Joi.string().required().max(255),
    title_en: Joi.string().allow('', null).max(255),
    content: Joi.string().allow('', null),
    content_en: Joi.string().allow('', null),
    status: Joi.string().valid('draft', 'published').default('draft'),
    published_at: Joi.date().iso().allow(null).optional(),
    gallery: gallerySchema
  }),

  event: Joi.object({
    title: Joi.string().required().max(255),
    title_en: Joi.string().allow('', null).max(255),
    description: Joi.string().allow('', null),
    description_en: Joi.string().allow('', null),
    start_date: Joi.date().iso().allow(null).optional(),
    end_date: Joi.date().iso().allow(null).optional(),
    location: Joi.string().allow('', null).max(255),
    location_en: Joi.string().allow('', null).max(255),
    status: Joi.string().valid('draft', 'published').default('draft'),
    gallery: gallerySchema
  }),

  directory: Joi.object({
    category: Joi.string().required().max(100),
    title: Joi.string().required().max(255),
    title_en: Joi.string().allow('', null).max(255),
    subtitle: Joi.string().allow('', null).max(255),
    subtitle_en: Joi.string().allow('', null).max(255),
    description: Joi.string().allow('', null),
    description_en: Joi.string().allow('', null),
    contact_info: Joi.string().allow('', null),
    contact_info_en: Joi.string().allow('', null),
    stats: Joi.alternatives().try(Joi.object(), Joi.string()).allow(null).optional(),
    status: Joi.string().valid('draft', 'published').default('draft'),
    sort_order: Joi.number().integer().default(0),
    gallery: gallerySchema
  }),

  page: Joi.object({
    hero_title: Joi.string().required().max(255),
    hero_title_en: Joi.string().allow('', null).max(255),
    hero_subtitle: Joi.string().allow('', null).max(255),
    hero_subtitle_en: Joi.string().allow('', null).max(255),
    hero_image_url: Joi.string().allow('', null).max(255),
    intro_title: Joi.string().allow('', null).max(255),
    intro_title_en: Joi.string().allow('', null).max(255),
    intro_text: Joi.string().allow('', null),
    intro_text_en: Joi.string().allow('', null)
  })
};

// Middleware wrapper
const validate = (schemaName) => {
  return (req, res, next) => {
    const schema = schemas[schemaName];
    if (!schema) {
      return next(new Error(`Schema '${schemaName}' not found`));
    }

    const { error, value } = schema.validate(req.body, { abortEarly: false, stripUnknown: true });
    
    if (error) {
      const errorMessages = error.details.map(d => d.message);
      return res.status(400).json({ error: 'Errore di validazione', details: errorMessages });
    }

    // Sostituisce il body con i valori validati (e parsati in caso di JSON strings)
    req.body = value;
    next();
  };
};

module.exports = validate;
