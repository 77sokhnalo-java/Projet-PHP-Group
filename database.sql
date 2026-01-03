
-- 1. Créer la base de données
CREATE DATABASE IF NOT EXISTS Examen_S2_2016;
USE Examen_S2_2016;

-- 2. Créer la table Article
CREATE TABLE Article (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE NOT NULL,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    qteStock INT DEFAULT 0,
    prix INT DEFAULT 0  -- Prix en FCFA (nombre entier)
);

-- 3. Créer la table Vente
CREATE TABLE Vente (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(20) UNIQUE NOT NULL,
    date DATE NOT NULL,
    nom VARCHAR(100) NOT NULL,
    adresse TEXT NOT NULL
);

-- 4. Créer la table VenteArticle
CREATE TABLE VenteArticle (
    idArt INT,
    idVen INT,
    qteVendue INT NOT NULL,
    PRIMARY KEY (idArt, idVen),
    FOREIGN KEY (idArt) REFERENCES Article(id),
    FOREIGN KEY (idVen) REFERENCES Vente(id)
);

-- 5. Insérer 5 articles avec prix en FCFA
INSERT INTO Article (code, nom, description, qteStock, prix) VALUES
('A001', 'Ordinateur Portable', 'PC portable 15 pouces, 8GB RAM, 512GB SSD', 10, 200000),
('A002', 'Souris USB', 'Souris sans fil, ergonomique', 50, 25000),
('A003', 'Clavier Mécanique', 'Clavier gaming RGB, rétro-éclairé', 25, 50000),
('A004', 'Écran 24"', 'Écran Full HD, 144Hz', 15, 75000),
('A005', 'Casque Audio', 'Casque avec micro, réduction de bruit', 30, 35000);