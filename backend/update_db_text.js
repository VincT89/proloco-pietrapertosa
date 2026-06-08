const db = require('./src/config/db');

async function fixHtml() {
  try {
    const [items] = await db.query('SELECT id, description_en FROM directory_items WHERE description_en IS NOT NULL');
    
    for (const item of items) {
      if (item.description_en.includes('<p>')) {
        const cleaned = item.description_en.replace(/<\/?p>/g, '');
        await db.query('UPDATE directory_items SET description_en = ? WHERE id = ?', [cleaned, item.id]);
      }
    }
    
    console.log("Database HTML tags removed successfully!");
    process.exit(0);
  } catch (error) {
    console.error("Error fixing db:", error);
    process.exit(1);
  }
}

fixHtml();
