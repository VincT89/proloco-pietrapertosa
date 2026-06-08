-- Inserimento utente Admin predefinito
-- Attenzione: La password 'admin123' deve essere passata ad un generatore bcrypt!
-- Hash per 'admin123' -> $2a$10$X... (Lo aggiorneremo col backend, intanto usiamo un placeholder)
INSERT INTO users (name, email, password_hash, role) 
VALUES ('Amministratore', 'admin@prolocopietrapertosa.it', '$2b$10$hL7P3Jz55t95b93K11KqDOlF6SST2aYl8O781e6tX4A1h3/3K6jO.', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Inserimento Pagine di base
INSERT INTO pages (slug, title, description) VALUES
('home', 'Home Page', 'La pagina principale del sito'),
('pro-loco', 'Pro Loco', 'Informazioni sull\'associazione'),
('comunita', 'Comunità', 'Le associazioni e i volontari'),
('territorio', 'Territorio', 'Il paese e i dintorni'),
('sapori', 'Sapori', 'Enogastronomia locale'),
('scopri', 'Scopri & Vivi', 'Luoghi di interesse turistico')
ON DUPLICATE KEY UPDATE id=id;
