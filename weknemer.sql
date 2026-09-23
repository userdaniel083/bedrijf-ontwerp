USE taxi_db;

INSERT INTO gebruiker (
	email,
	gebruikersnaam,
	wachtwoord,
	rol
)
VALUES (
	'admin@taxi.nl',
	'Admin',
	'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi',
	'Admin'
)
ON DUPLICATE KEY UPDATE
	gebruikersnaam = 'Admin',
	rol = 'Admin';


