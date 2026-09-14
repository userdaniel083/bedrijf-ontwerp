CREATE DATABASE taxi_db;

CREATE TABLE gebruiker (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    gebruikersnaam VARCHAR(50) NOT NULL,
    wachtwoord VARCHAR(255) NOT NULL,
    rol ENUM('Klant', 'Chauffeur', 'Admin') DEFAULT 'Klant'
);

CREATE TABLE ritten (
    rit_id INT AUTO_INCREMENT PRIMARY KEY,
    klant_id INT NOT NULL, 
    chauffeur_id INT NULL, 
    ophaaladres VARCHAR(255) NOT NULL,
    bestemming VARCHAR(255) NOT NULL,
    datum DATE NOT NULL,
    tijdstip TIME NOT NULL,
    aantal_passagiers INT NOT NULL DEFAULT 1,
    status ENUM('Nieuw', 'Gepland', 'Onderweg', 'Voltooid', 'Geannuleerd') DEFAULT 'Nieuw',
    
    FOREIGN KEY (klant_id) REFERENCES gebruiker(ID),
    FOREIGN KEY (chauffeur_id) REFERENCES gebruiker(ID)
);

CREATE TABLE chauffeurs_status (
    status_id INT AUTO_INCREMENT PRIMARY KEY,
    chauffeur_id INT NOT NULL,
    is_aanwezig BOOLEAN DEFAULT TRUE, 
    laatste_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (chauffeur_id) REFERENCES gebruiker(ID)
);