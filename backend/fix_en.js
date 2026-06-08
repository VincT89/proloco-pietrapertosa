const db = require('./src/config/db');

async function fixEn() {
  try {
    const pages = [
      {
        slug: 'comunita',
        hero_title_en: 'The Community',
        hero_subtitle_en: 'VOLUNTEER GROUPS AND ASSOCIATIONS IN PIETRAPERTOSA',
        intro_title_en: 'The realities of the village',
        intro_text_en: 'List of associations and groups that animate our land.'
      },
      {
        slug: 'scopri',
        hero_title_en: 'Discover & Live',
        hero_subtitle_en: 'A JOURNEY THROUGH THE LUCANIAN DOLOMITES',
        intro_title_en: 'Must-see places',
        intro_text_en: 'Explore the historical and natural wonders of our village. And when it’s time to rest or eat, rely on our welcoming facilities.'
      },
      {
        slug: 'territorio',
        hero_title_en: 'The Territory',
        hero_subtitle_en: 'HANDS THAT CREATE AND HANDS THAT CULTIVATE',
        intro_title_en: 'Artisans and Companies',
        intro_text_en: 'Behind the flavors and artifacts of our land are the stories of extraordinary people. Discover the farms, food trucks, and master artisans of Pietrapertosa.'
      },
      {
        slug: 'sapori',
        hero_title_en: 'Local Tastes and Products',
        hero_subtitle_en: 'GASTRONOMIC TRADITION OF PIETRAPERTOSA',
        intro_title_en: 'Food Culture',
        intro_text_en: 'The intertwining of raw materials, peasant work, and ancient recipes of our territory.'
      }
    ];

    for (const p of pages) {
      await db.query(`
        UPDATE page_settings 
        SET hero_title_en = ?, hero_subtitle_en = ?, intro_title_en = ?, intro_text_en = ?
        WHERE page_slug = ?
      `, [p.hero_title_en, p.hero_subtitle_en, p.intro_title_en, p.intro_text_en, p.slug]);
      console.log('Updated', p.slug);
    }

    console.log('Done!');
    process.exit(0);
  } catch (err) {
    console.error(err);
    process.exit(1);
  }
}

fixEn();
