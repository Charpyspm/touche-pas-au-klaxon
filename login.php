<?php
session_start();

require_once __DIR__ . '/models/User.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (!empty($email)) {
        $userModel = new User();
        $user = $userModel->getUserByEmail($email);
        
        if ($user) {
            // Utilisateur trouvé - créer la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['user_is_admin'] = $user['is_admin'];
            
            // Rediriger vers la page d'accueil
            header('Location: index.php');
            exit();
        } else {
            // Utilisateur non trouvé
            $_SESSION['error_message'] = "Aucun utilisateur trouvé avec cet email.";
            header('Location: connexion.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Veuillez entrer votre email.";
        header('Location: connexion.php');
        exit();
    }
} else {
    // Si accès direct sans POST, rediriger vers la page de connexion
    header('Location: connexion.php');
    exit();
}
