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

-- Employés avec mots de passe générés automatiquement (première lettre prénom + première lettre nom)
-- Alexandre Martin -> mot de passe: am
-- Sophie Dubois -> mot de passe: sd
-- Julien Bernard -> mot de passe: jb
-- Camille Moreau -> mot de passe: cm
-- Lucie Lefèvre -> mot de passe: ll
-- Thomas Leroy -> mot de passe: tl
-- Chloé Roux -> mot de passe: cr
-- Maxime Petit -> mot de passe: mp
-- Laura Garnier -> mot de passe: lg
-- Antoine Dupuis -> mot de passe: ad
-- Emma Lefebvre -> mot de passe: el
-- Louis Fontaine -> mot de passe: lf
-- Clara Chevalier -> mot de passe: cc
-- Nicolas Robin -> mot de passe: nr
-- Marine Gauthier -> mot de passe: mg
-- Pierre Fournier -> mot de passe: pf
-- Sarah Girard -> mot de passe: sg
-- Hugo Lambert -> mot de passe: hl
-- Julie Masson -> mot de passe: jm
-- Arthur Henry -> mot de passe: ah
INSERT INTO users (nom, prenom, telephone, email, password, is_admin) VALUES
('Martin', 'Alexandre', '0612345678', 'alexandre.martin@email.fr', '$2y$10$2nI.BDTIIdbwD0Pp/XG4aew7IVYEhV.bOFVNBFkprci2BWuCI8WR2', FALSE),
('Dubois', 'Sophie', '0698765432', 'sophie.dubois@email.fr', '$2y$10$wLIF18PlKZXb4nhqaCt5IOaKLohuRfK2oPMMfPrjsSA1jet7W.h7K', FALSE),
('Bernard', 'Julien', '0622446688', 'julien.bernard@email.fr', '$2y$10$waLWXD3joGRqHR4dDs/4d.FdPagdUljPxc.wXMkjpDEsnihq5RKRS', FALSE),
('Moreau', 'Camille', '0611223344', 'camille.moreau@email.fr', '$2y$10$5bCxpxoWr9fTWognQQouluudTcGeKY..zCI3Rp.xFzg56.zrIF1VO', FALSE),
('Lefèvre', 'Lucie', '0777889900', 'lucie.lefevre@email.fr', '$2y$10$kcN6e3GzVrWVLQUC01ld..l8dCWZxhgUbtNSvjK7ssWSBdD56QLBC', FALSE),
('Leroy', 'Thomas', '0655443322', 'thomas.leroy@email.fr', '$2y$10$ulriGCzVeqDtPBdMkAI8QuyDwtpy1lEKyq70rIfrX/6BKAGREopCC', FALSE),
('Roux', 'Chloé', '0633221199', 'chloe.roux@email.fr', '$2y$10$LoYfrRrjxEPuAuaVzG2N8ukMyuqr66X/ma6AqLXLnd6IxlJXMZYmW', FALSE),
('Petit', 'Maxime', '0766778899', 'maxime.petit@email.fr', '$2y$10$ZgynCqci3F/7iCGLGTaa2.dqN2soI2Sb67ZLEBZxFESEcvizGVB5e', FALSE),
('Garnier', 'Laura', '0688776655', 'laura.garnier@email.fr', '$2y$10$LP8c3KRwJQkkxMtvn5UXwuahOpiBoeZV0T9dO8QPOtkUylvhLidiW', FALSE),
('Dupuis', 'Antoine', '0744556677', 'antoine.dupuis@email.fr', '$2y$10$.Gl5p6VCpnHy8Ws5dkA3me7u.PZjaKGr2F.abJrzLsXTQxGmN/szO', FALSE),
('Lefebvre', 'Emma', '0699887766', 'emma.lefebvre@email.fr', '$2y$10$DOKppeUg2rOQ6UdFoRD7DurTwkc4r1Hiy8libooLghb5gNRsfBG5e', FALSE),
('Fontaine', 'Louis', '0655667788', 'louis.fontaine@email.fr', '$2y$10$u4JxC5mUUAERnbEh9tUJXO6SfpvHuiJLc/AmpIc3bgIAAKt584yhe', FALSE),
('Chevalier', 'Clara', '0788990011', 'clara.chevalier@email.fr', '$2y$10$WicdpaiIXTVlLomM6xC0DuVGAsJ.TY4aFpJxYk9QyDr8Cuf63qcNG', FALSE),
('Robin', 'Nicolas', '0644332211', 'nicolas.robin@email.fr', '$2y$10$/5CyxfCkJPndCt.tymA2KOR/NQYbsthTHNVs8vhEC4nBrWu6l5yVe', FALSE),
('Gauthier', 'Marine', '0677889922', 'marine.gauthier@email.fr', '$2y$10$5A.ngFOnNPGZeCXLmV0.yuVEEMV0azi8IF0n.SjiqAZThfMg9uMXm', FALSE),
('Fournier', 'Pierre', '0722334455', 'pierre.fournier@email.fr', '$2y$10$zI0341mkN8X7kk9SgFF4SOoMbqWYvTCWFVY9cLFxTJmIGX/sXH93e', FALSE),
('Girard', 'Sarah', '0688665544', 'sarah.girard@email.fr', '$2y$10$Kt0jIpNwlb3f9n5IcEZThO5oW69TLan04ZSJQo47ZKXlkDT3hxDfe', FALSE),
('Lambert', 'Hugo', '0611223366', 'hugo.lambert@email.fr', '$2y$10$bmZ7Q19HGTDpjVW2tWSKJ.1ObGKIaweYUilDssbFgIqVnpNvn/eBm', FALSE),
('Masson', 'Julie', '0733445566', 'julie.masson@email.fr', '$2y$10$ISnAfRCaq80b2bK29TrYhO.WKXkMwfCnoQdY2kJJOzM5DsKpshmJy', FALSE),
('Henry', 'Arthur', '0666554433', 'arthur.henry@email.fr', '$2y$10$lTOF/M67OP.bd6g9FPPxs.8l7EABcZ.QE6R221xyNGg7i14ebsRNa', FALSE);

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
