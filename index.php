<?php
require_once __DIR__ . '/models/Trajet.php';

// Récupérer tous les trajets
$trajetModel = new Trajet();
$trajets = $trajetModel->getAllTrajets();

// Afficher la vue
require_once __DIR__ . '/views/home.php';
