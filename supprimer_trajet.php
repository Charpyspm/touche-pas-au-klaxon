<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$trajet_id = $_GET['id'] ?? null;

if (!$trajet_id) {
    $_SESSION['error_message'] = "Trajet introuvable.";
    header('Location: index.php');
    exit();
}

require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Vérifier que le trajet appartient à l'utilisateur
    $checkQuery = "SELECT id FROM trajets WHERE id = :id AND user_id = :user_id";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':id', $trajet_id);
    $checkStmt->bindParam(':user_id', $_SESSION['user_id']);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() === 0) {
        $_SESSION['error_message'] = "Vous n'avez pas la permission de supprimer ce trajet.";
        header('Location: index.php');
        exit();
    }
    
    // Supprimer le trajet
    $deleteQuery = "DELETE FROM trajets WHERE id = :id AND user_id = :user_id";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bindParam(':id', $trajet_id);
    $deleteStmt->bindParam(':user_id', $_SESSION['user_id']);
    
    if ($deleteStmt->execute()) {
        $_SESSION['success_message'] = "Trajet supprimé avec succès !";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression du trajet.";
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
}

header('Location: index.php');
exit();
