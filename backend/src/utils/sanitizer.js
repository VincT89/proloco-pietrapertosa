const sanitizeHtml = require('sanitize-html');

const sanitizeConfig = {
  allowedTags: sanitizeHtml.defaults.allowedTags.concat([
    'img', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'u', 's', 'span', 'div', 'p', 'br'
  ]),
  allowedAttributes: {
    ...sanitizeHtml.defaults.allowedAttributes,
    'img': ['src', 'alt', 'width', 'height'],
    'p': ['class', 'style'],
    'span': ['class', 'style'],
    'h1': ['class', 'style'],
    'h2': ['class', 'style'],
    'h3': ['class', 'style'],
    'h4': ['class', 'style'],
    'h5': ['class', 'style'],
    'h6': ['class', 'style'],
    'div': ['class', 'style'],
  },
  allowedClasses: {
    '*': [/^ql-/] // Accetta solo classi generate da Quill (es. ql-align-center, ql-size-large)
  },
  allowedStyles: {
    '*': {
      'color': [/^#(0x)?[0-9a-f]+$/i, /^rgb\(/],
      'background-color': [/^#(0x)?[0-9a-f]+$/i, /^rgb\(/],
      'text-align': [/^left$/, /^right$/, /^center$/, /^justify$/]
    }
  },
  allowedSchemes: ['http', 'https', 'ftp', 'mailto', 'tel'],
  allowProtocolRelative: true,
  nonTextTags: [ 'style', 'script', 'textarea', 'option', 'noscript' ]
};

exports.cleanHtml = (dirty) => {
  if (typeof dirty !== 'string') return dirty;
  return sanitizeHtml(dirty, sanitizeConfig);
};
