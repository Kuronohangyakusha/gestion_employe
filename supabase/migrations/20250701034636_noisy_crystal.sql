-- Script de création de la base de données
CREATE DATABASE IF NOT EXISTS gestion_employes;
USE gestion_employes;

-- Table service
CREATE TABLE IF NOT EXISTS service (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    manager_id INT NULL
);

-- Table employe
CREATE TABLE IF NOT EXISTS employe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    salaire DECIMAL(10,2) NOT NULL,
    service_id INT NULL,
    prime DECIMAL(10,2) NULL,
    specialite VARCHAR(2) NULL,
    login VARCHAR(50) NULL,
    password VARCHAR(255) NULL,
    type ENUM('Admin', 'Manager', 'Developpeur') NOT NULL,
    FOREIGN KEY (service_id) REFERENCES service(id)
);

-- Données de test
INSERT INTO service (nom) VALUES 
('Informatique'),
('Ressources Humaines'),
('Marketing');

INSERT INTO employe (nom, telephone, salaire, service_id, prime, specialite, login, password, type) VALUES
('Alice Martin', '0123456789', 4000, 1, NULL, NULL, 'admin', 'password123', 'Admin'),
('Bob Dupont', '0123456790', 2500, 1, 500, NULL, NULL, NULL, 'Manager'),
('Charlie Durand', '0123456791', 1800, 1, NULL, 'FS', NULL, NULL, 'Developpeur'),
('Diana Moreau', '0123456792', 1800, 2, NULL, 'FE', NULL, NULL, 'Developpeur');