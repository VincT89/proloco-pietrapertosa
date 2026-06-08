/**
 * Esegue il parsing di una stringa JSON in modo sicuro.
 * Se la stringa è vuota, nulla o il parsing fallisce, restituisce il valore di fallback.
 * 
 * @param {string} value La stringa JSON da parsare
 * @param {any} fallback Il valore di default in caso di errore (es. array vuoto [])
 * @returns {any}
 */
export function safeJsonParse(value, fallback = null) {
  if (!value) return fallback;
  if (typeof value === 'object') return value; // Già oggetto

  try {
    return JSON.parse(value);
  } catch (err) {
    console.error("Errore durante il parsing del JSON:", err, "Valore:", value);
    return fallback;
  }
}
