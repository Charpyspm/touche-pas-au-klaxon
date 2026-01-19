<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit();
}

$trajet_id = $_GET['id'] ?? null;

if (!$trajet_id) {
    echo json_encode(['error' => 'Trajet introuvable']);
    exit();
}

require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Récupérer le trajet avec les informations de l'auteur
    $query = "SELECT 
                t.*,
                a_depart.nom as ville_depart,
                a_arrivee.nom as ville_destination,
                u.prenom as conducteur_prenom,
                u.nom as conducteur_nom,
                u.telephone as conducteur_telephone,
                u.email as conducteur_email
              FROM trajets t
              LEFT JOIN agences a_depart ON t.agence_depart_id = a_depart.id
              LEFT JOIN agences a_arrivee ON t.agence_arrivee_id = a_arrivee.id
              LEFT JOIN users u ON t.user_id = u.id
              WHERE t.id = :id";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $trajet_id);
    $stmt->execute();
    $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$trajet) {
        echo json_encode(['error' => 'Trajet introuvable']);
        exit();
    }
    
    // Retourner les données en JSON
    echo json_encode([
        'success' => true,
        'auteur' => $trajet['conducteur_prenom'] . ' ' . $trajet['conducteur_nom'],
        'telephone' => $trajet['conducteur_telephone'],
        'email' => $trajet['conducteur_email'],
        'places_total' => $trajet['nombre_places_total'],
        'places_disponibles' => $trajet['nombre_places_disponibles'],
        'depart' => $trajet['ville_depart'],
        'destination' => $trajet['ville_destination'],
        'date_depart' => date('d/m/Y à H:i', strtotime($trajet['date_depart'])),
        'date_arrivee' => date('d/m/Y à H:i', strtotime($trajet['date_arrivee']))
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
