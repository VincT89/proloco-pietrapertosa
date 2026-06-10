const mysql = require('mysql2/promise');
require('dotenv').config();

async function run() {
  const conn = await mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    port: process.env.DB_PORT || 3306
  });

  await conn.query('UPDATE page_settings SET hero_title = REPLACE(hero_title, "├á", "à")');
  await conn.query('UPDATE page_settings SET hero_title = REPLACE(hero_title, "├è", "È")');
  await conn.query('UPDATE page_settings SET intro_title = REPLACE(intro_title, "├á", "à")');
  await conn.query('UPDATE page_settings SET intro_text = REPLACE(intro_text, "├á", "à")');
  await conn.query('UPDATE page_settings SET intro_text = REPLACE(intro_text, "├¿", "è")');
  
  await conn.query('UPDATE pages SET title = REPLACE(title, "├á", "à")');

  await conn.query('UPDATE directory_items SET title = REPLACE(title, "├á", "à")');
  await conn.query('UPDATE directory_items SET subtitle = REPLACE(subtitle, "├á", "à")');

  await conn.query('UPDATE events SET title = REPLACE(title, "├á", "à")');
  await conn.query('UPDATE events SET description = REPLACE(description, "├á", "à")');

  await conn.query('UPDATE news SET title = REPLACE(title, "├á", "à")');
  await conn.query('UPDATE news SET content = REPLACE(content, "├á", "à")');

  console.log('Fixed common encoding issues');
  await conn.end();
}

run().catch(console.error);
