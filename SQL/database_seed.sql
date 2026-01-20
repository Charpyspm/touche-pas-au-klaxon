-- =============================================================================
-- Jeu d'essais : TOUCHE PAS AU KLAXON
-- Alimentation de la base de données avec les données de test
-- =============================================================================

USE touche_pas_au_klaxon;

-- =============================================================================
-- Nettoyage des tables (pour éviter les doublons)
-- =============================================================================
DELETE FROM trajets;
DELETE FROM users;
DELETE FROM agences;

-- Réinitialiser les auto-increment
ALTER TABLE trajets AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE agences AUTO_INCREMENT = 1;

-- =============================================================================
-- Insertion des agences (villes/sites)
-- =============================================================================
INSERT INTO agences (nom) VALUES
('Paris'),
('Lyon'),
('Marseille'),
('Toulouse'),
('Nice'),
('Nantes'),
('Strasbourg'),
('Montpellier'),
('Bordeaux'),
('Lille'),
('Rennes'),
('Reims');

-- =============================================================================
-- Insertion des utilisateurs (employés)
-- =============================================================================
-- Compte admin avec mot de passe "admin123" (hashé)
INSERT INTO users (nom, prenom, telephone, email, password, is_admin) VALUES
('Admin', 'Administrateur', '0600000000', 'admin@admin.fr', '$2y$10$pCbVooG28HbfsrTb4B6Q6uQAUusQOECGDlhL0EH4C7TyF9Ma0D3oa', TRUE);

-- Employés normaux (sans mot de passe)
INSERT INTO users (nom, prenom, telephone, email, is_admin) VALUES
('Martin', 'Alexandre', '0612345678', 'alexandre.martin@email.fr', FALSE),
('Dubois', 'Sophie', '0698765432', 'sophie.dubois@email.fr', FALSE),
('Bernard', 'Julien', '0622446688', 'julien.bernard@email.fr', FALSE),
('Moreau', 'Camille', '0611223344', 'camille.moreau@email.fr', FALSE),
('Lefèvre', 'Lucie', '0777889900', 'lucie.lefevre@email.fr', FALSE),
('Leroy', 'Thomas', '0655443322', 'thomas.leroy@email.fr', FALSE),
('Roux', 'Chloé', '0633221199', 'chloe.roux@email.fr', FALSE),
('Petit', 'Maxime', '0766778899', 'maxime.petit@email.fr', FALSE),
('Garnier', 'Laura', '0688776655', 'laura.garnier@email.fr', FALSE),
('Dupuis', 'Antoine', '0744556677', 'antoine.dupuis@email.fr', FALSE),
('Lefebvre', 'Emma', '0699887766', 'emma.lefebvre@email.fr', FALSE),
('Fontaine', 'Louis', '0655667788', 'louis.fontaine@email.fr', FALSE),
('Chevalier', 'Clara', '0788990011', 'clara.chevalier@email.fr', FALSE),
('Robin', 'Nicolas', '0644332211', 'nicolas.robin@email.fr', FALSE),
('Gauthier', 'Marine', '0677889922', 'marine.gauthier@email.fr', FALSE),
('Fournier', 'Pierre', '0722334455', 'pierre.fournier@email.fr', FALSE),
('Girard', 'Sarah', '0688665544', 'sarah.girard@email.fr', FALSE),
('Lambert', 'Hugo', '0611223366', 'hugo.lambert@email.fr', FALSE),
('Masson', 'Julie', '0733445566', 'julie.masson@email.fr', FALSE),
('Henry', 'Arthur', '0666554433', 'arthur.henry@email.fr', FALSE);

-- =============================================================================
-- Trajets exemples (optionnel - à commenter/décommenter selon besoin)
-- =============================================================================
-- Quelques trajets de démonstration pour tester l'application

-- INSERT INTO trajets (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, nombre_places_total, nombre_places_disponibles, user_id) VALUES
-- -- Trajet Paris -> Lyon
-- (1, 2, '2026-01-25 08:00:00', '2026-01-25 12:00:00', 4, 3, 1),
-- -- Trajet Marseille -> Nice
-- (3, 5, '2026-01-26 09:00:00', '2026-01-26 11:30:00', 3, 2, 2),
-- -- Trajet Toulouse -> Bordeaux
-- (4, 9, '2026-01-27 14:00:00', '2026-01-27 16:30:00', 4, 4, 3),
-- -- Trajet Lille -> Paris
-- (10, 1, '2026-01-28 07:30:00', '2026-01-28 10:00:00', 5, 1, 4),
-- -- Trajet Nantes -> Rennes
-- (6, 11, '2026-01-29 10:00:00', '2026-01-29 11:30:00', 3, 3, 5);
