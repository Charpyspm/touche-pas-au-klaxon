-- =============================================================================
-- Base de données : TOUCHE PAS AU KLAXON
-- Application de covoiturage intra-entreprise
-- =============================================================================

-- Suppression de la base si elle existe déjà
DROP DATABASE IF EXISTS touche_pas_au_klaxon;

-- Création de la base de données
CREATE DATABASE touche_pas_au_klaxon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Utilisation de la base
USE touche_pas_au_klaxon;

-- =============================================================================
-- Table : users (employés)
-- =============================================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) DEFAULT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Table : agences (villes/sites)
-- =============================================================================
CREATE TABLE agences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Table : trajets
-- =============================================================================
CREATE TABLE trajets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agence_depart_id INT NOT NULL,
    agence_arrivee_id INT NOT NULL,
    date_depart DATETIME NOT NULL,
    date_arrivee DATETIME NOT NULL,
    nombre_places_total INT NOT NULL,
    nombre_places_disponibles INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Contraintes de clés étrangères
    FOREIGN KEY (agence_depart_id) REFERENCES agences(id) ON DELETE CASCADE,
    FOREIGN KEY (agence_arrivee_id) REFERENCES agences(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- Contraintes de validation
    CHECK (agence_depart_id != agence_arrivee_id),
    CHECK (date_arrivee > date_depart),
    CHECK (nombre_places_total > 0),
    CHECK (nombre_places_disponibles >= 0),
    CHECK (nombre_places_disponibles <= nombre_places_total),
    
    -- Index pour optimisation des requêtes
    INDEX idx_date_depart (date_depart),
    INDEX idx_agence_depart (agence_depart_id),
    INDEX idx_agence_arrivee (agence_arrivee_id),
    INDEX idx_user (user_id),
    INDEX idx_places_disponibles (nombre_places_disponibles)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
