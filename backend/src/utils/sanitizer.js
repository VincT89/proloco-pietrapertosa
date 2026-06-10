const sanitizeHtml = require('sanitize-html');

const sanitizeConfig = {
  allowedTags: sanitizeHtml.defaults.allowedTags.concat([
    'img', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'u', 's', 'span', 'div', 'p', 'br'
  ]),
  allowedAttributes: {
    ...sanitizeHtml.defaults.allowedAttributes,
    'img': ['src', 'alt', 'width', 'height'],
    'p': ['class'],
    'span': ['class'],
    'h1': ['class'],
    'h2': ['class'],
    'h3': ['class'],
    'h4': ['class'],
    'h5': ['class'],
    'h6': ['class'],
    'div': ['class'],
  },
  allowedClasses: {
    '*': [/^ql-/] // Accetta solo classi generate da Quill (es. ql-align-center, ql-size-large)
  },
  allowedStyles: {},
  allowedSchemes: ['http', 'https', 'ftp', 'mailto', 'tel'],
  allowProtocolRelative: true,
  nonTextTags: [ 'style', 'script', 'textarea', 'option', 'noscript' ]
};

exports.cleanHtml = (dirty) => {
  if (typeof dirty !== 'string') return dirty;
  return sanitizeHtml(dirty, sanitizeConfig);
};
