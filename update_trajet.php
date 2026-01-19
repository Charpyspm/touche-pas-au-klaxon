<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/config/database.php';
    
    // Récupérer les données du formulaire
    $trajet_id = $_POST['id'] ?? '';
    $agence_depart_id = $_POST['agence_depart_id'] ?? '';
    $agence_arrivee_id = $_POST['agence_arrivee_id'] ?? '';
    $date_depart = $_POST['date_depart'] ?? '';
    $heure_depart = $_POST['heure_depart'] ?? '';
    $date_arrivee = $_POST['date_arrivee'] ?? '';
    $heure_arrivee = $_POST['heure_arrivee'] ?? '';
    $nombre_places_total = $_POST['nombre_places_total'] ?? '';
    $user_id = $_SESSION['user_id'];
    
    // Validation
    if (empty($trajet_id) || empty($agence_depart_id) || empty($agence_arrivee_id) || 
        empty($date_depart) || empty($heure_depart) || empty($date_arrivee) || 
        empty($heure_arrivee) || empty($nombre_places_total)) {
        $_SESSION['error_message'] = "Tous les champs sont obligatoires.";
        header('Location: modifier_trajet.php?id=' . $trajet_id);
        exit();
    }
    
    // Vérifier que les agences sont différentes
    if ($agence_depart_id === $agence_arrivee_id) {
        $_SESSION['error_message'] = "L'agence de départ et d'arrivée doivent être différentes.";
        header('Location: modifier_trajet.php?id=' . $trajet_id);
        exit();
    }
    
    // Combiner date et heure
    $datetime_depart = $date_depart . ' ' . $heure_depart . ':00';
    $datetime_arrivee = $date_arrivee . ' ' . $heure_arrivee . ':00';
    
    // Vérifier que la date d'arrivée est après la date de départ
    if (strtotime($datetime_arrivee) <= strtotime($datetime_depart)) {
        $_SESSION['error_message'] = "La date et l'heure d'arrivée doivent être postérieures à celles de départ.";
        header('Location: modifier_trajet.php?id=' . $trajet_id);
        exit();
    }
    
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Vérifier que le trajet appartient à l'utilisateur
        $checkQuery = "SELECT id FROM trajets WHERE id = :id AND user_id = :user_id";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':id', $trajet_id);
        $checkStmt->bindParam(':user_id', $user_id);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() === 0) {
            $_SESSION['error_message'] = "Vous n'avez pas la permission de modifier ce trajet.";
            header('Location: index.php');
            exit();
        }
        
        // Mettre à jour le trajet
        $query = "UPDATE trajets 
                  SET agence_depart_id = :agence_depart_id, 
                      agence_arrivee_id = :agence_arrivee_id, 
                      date_depart = :date_depart, 
                      date_arrivee = :date_arrivee, 
                      nombre_places_total = :nombre_places_total,
                      nombre_places_disponibles = :nombre_places_disponibles
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':agence_depart_id', $agence_depart_id);
        $stmt->bindParam(':agence_arrivee_id', $agence_arrivee_id);
        $stmt->bindParam(':date_depart', $datetime_depart);
        $stmt->bindParam(':date_arrivee', $datetime_arrivee);
        $stmt->bindParam(':nombre_places_total', $nombre_places_total);
        $stmt->bindParam(':nombre_places_disponibles', $nombre_places_total);
        $stmt->bindParam(':id', $trajet_id);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Trajet modifié avec succès !";
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error_message'] = "Erreur lors de la modification du trajet.";
            header('Location: modifier_trajet.php?id=' . $trajet_id);
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
        header('Location: modifier_trajet.php?id=' . $trajet_id);
        exit();
    }
} else {
    // Si accès direct sans POST, rediriger
    header('Location: index.php');
    exit();
}
