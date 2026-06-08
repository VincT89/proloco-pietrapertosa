const db = require('./src/config/db');

async function translateDB() {
  try {
    const translations = [
      {
        id: 1,
        title_en: "Musical Association 'I Suoni delle Dolomiti'",
        subtitle_en: "Musical Band",
        description_en: "<p>The historical voice of the village. Founded decades ago, the musical band animates every local festival and celebration, bringing the traditional sounds of the Lucanian Dolomites.</p>",
        contact_info_en: "Local Musical Band"
      },
      {
        id: 2,
        title_en: "Civil Protection",
        subtitle_en: "Rescue and Assistance",
        description_en: "<p>Tireless volunteers engaged daily in the protection of the territory and assistance to the population. A fundamental pillar for the safety of Pietrapertosa.</p>",
        contact_info_en: "Public Data"
      },
      {
        id: 3,
        title_en: "The Maggio Festival",
        subtitle_en: "May",
        description_en: "<p>The centuries-old festival of 'Maggio' links arboreal cults and devotion to Saint Anthony. A large trunk and a top are cut in the woods, transported to the village and 'married' in the square, in a ritual that celebrates fertility and the cycle of nature.</p>",
        contact_info_en: ""
      },
      {
        id: 4,
        title_en: "The Arabata",
        subtitle_en: "August",
        description_en: "<p>Pietrapertosa takes a step back in time, among oriental scents, belly dancers, fakirs and storytellers. The Arabatana, the oldest neighborhood, comes alive to celebrate the Arab origins of the village in a unique and evocative atmosphere.</p>",
        contact_info_en: ""
      }
    ];

    for (const item of translations) {
      await db.query(
        `UPDATE directory_items 
         SET title_en = ?, subtitle_en = ?, description_en = ?, contact_info_en = ? 
         WHERE id = ?`,
        [item.title_en, item.subtitle_en, item.description_en, item.contact_info_en, item.id]
      );
    }
    
    console.log("Database translated successfully!");
    process.exit(0);
  } catch (error) {
    console.error("Error translating db:", error);
    process.exit(1);
  }
}

translateDB();
