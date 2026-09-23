USE taxi_db;

INSERT INTO gebruiker (
	email,
	gebruikersnaam,
	wachtwoord,
	rol
)
VALUES (
	'admin@taxi.nl',
	'taxi_admin',
    -- Password is: password
	'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi',
	'Admin'
)
ON DUPLICATE KEY UPDATE
	gebruikersnaam = 'taxi_admin',
	wachtwoord = VALUES(wachtwoord),
	rol = 'Admin';


