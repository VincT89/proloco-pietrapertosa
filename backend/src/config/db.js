const mysql = require('mysql2');

const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'proloco_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Export a promise wrapper for easy async/await
const promisePool = pool.promise();

module.exports = promisePool;
