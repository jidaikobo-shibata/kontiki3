CREATE TABLE IF NOT EXISTS file (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	path TEXT NOT NULL, -- Physical file path
	description TEXT, -- Link text or alt text
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Upload date
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Update date
);
