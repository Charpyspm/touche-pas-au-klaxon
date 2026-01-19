<?php
session_start();

require_once __DIR__ . '/models/Trajet.php';

// Récupérer tous les trajets
$trajetModel = new Trajet();
$trajets = $trajetModel->getAllTrajets();

// Vérifier si l'utilisateur est connecté
$isConnected = isset($_SESSION['user_id']);
$userName = '';
if ($isConnected) {
    $userName = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
}

// Récupérer les messages
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Afficher la vue
require_once __DIR__ . '/views/home.php';
