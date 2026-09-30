USE taxi_db;

INSERT INTO gebruiker (
	email,
	gebruikersnaam,
	wachtwoord,
	rol
)
VALUES
	('chauffeur1@taxi.nl', 'chauffeur_01', 'password', 'Chauffeur'),
	('chauffeur2@taxi.nl', 'chauffeur_02', 'password', 'Chauffeur'),
	('chauffeur3@taxi.nl', 'chauffeur_03', 'password', 'Chauffeur'),
	('chauffeur4@taxi.nl', 'chauffeur_04', 'password', 'Chauffeur'),
	('chauffeur5@taxi.nl', 'chauffeur_05', 'password', 'Chauffeur')
ON DUPLICATE KEY UPDATE
	gebruikersnaam = VALUES(gebruikersnaam),
	wachtwoord = VALUES(wachtwoord),
	rol = VALUES(rol);
