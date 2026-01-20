<?php
/**
 * Point d'entrée principal de l'application
 * 
 * Front Controller qui gère le routage de toutes les requêtes
 * 
 * @package TouchePasAuKlaxon
 * @author Votre Nom
 * @version 1.0
 */

session_start();

/**
 * Routeur simple MVC
 * 
 * Traite le paramètre 'action' de l'URL pour déterminer quelle action exécuter
 */
$action = $_GET['action'] ?? 'home';

// Router les actions
switch ($action) {
    /**
     * Routes d'authentification
     * 
     * Gèrent la connexion et déconnexion des utilisateurs
     */
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;
        
    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
    
    /**
     * Routes des trajets
     * 
     * CRUD complet pour les trajets de covoiturage
     */
    case 'create':
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->create();
        break;
        
    case 'store':
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->store();
        break;
        
    case 'edit':
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->edit();
        break;
        
    case 'update':
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->update();
        break;
        
    case 'delete':
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->delete();
        break;
        
    case 'details':
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->details();
        break;
    
    /**
     * Route API - Liste des utilisateurs (admin uniquement)
     * 
     * Retourne un JSON avec tous les utilisateurs
     */
    case 'users':
        require_once __DIR__ . '/models/User.php';
        
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user_is_admin']) || !$_SESSION['user_is_admin']) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
            exit;
        }
        
        $userModel = new User();
        $users = $userModel->getAllUsers();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'users' => $users]);
        exit;
    
    /**
     * Route API - Liste des agences (admin uniquement)
     * 
     * Retourne un JSON avec toutes les agences
     */
    case 'agences':
        require_once __DIR__ . '/models/Agence.php';
        
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user_is_admin']) || !$_SESSION['user_is_admin']) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
            exit;
        }
        
        $agenceModel = new Agence();
        $agences = $agenceModel->getAllAgences();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'agences' => $agences]);
        exit;
    
    /**
     * Route API - Liste complète des trajets (admin uniquement)
     * 
     * Retourne tous les trajets y compris ceux sans places disponibles
     */
    case 'trajets_admin':
        require_once __DIR__ . '/models/Trajet.php';
        
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user_is_admin']) || !$_SESSION['user_is_admin']) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
            exit;
        }
        
        $trajetModel = new Trajet();
        $trajets = $trajetModel->getAllTrajets();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'trajets' => $trajets]);
        exit;
    
    /**
     * Route API - Création d'une agence (admin uniquement)
     * 
     * Crée une nouvelle agence dans la base de données
     */
    case 'agence_create':
        require_once __DIR__ . '/models/Agence.php';
        
        if (!isset($_SESSION['user_is_admin']) || !$_SESSION['user_is_admin']) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
            exit;
        }
        
        $nom = $_POST['nom'] ?? '';
        
        if (empty($nom)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Le nom est requis']);
            exit;
        }
        
        $agenceModel = new Agence();
        $result = $agenceModel->createAgence($nom);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        exit;
    
    /**
     * Route API - Modification d'une agence (admin uniquement)
     * 
     * Met à jour le nom d'une agence existante
     */
    case 'agence_update':
        require_once __DIR__ . '/models/Agence.php';
        
        if (!isset($_SESSION['user_is_admin']) || !$_SESSION['user_is_admin']) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
            exit;
        }
        
        $id = $_POST['id'] ?? '';
        $nom = $_POST['nom'] ?? '';
        
        if (empty($id) || empty($nom)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Données manquantes']);
            exit;
        }
        
        $agenceModel = new Agence();
        $result = $agenceModel->updateAgence($id, $nom);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        exit;
    
    /**
     * Route API - Suppression d'une agence (admin uniquement)
     * 
     * Supprime une agence si elle n'est pas utilisée dans des trajets
     */
    case 'agence_delete':
        require_once __DIR__ . '/models/Agence.php';
        
        if (!isset($_SESSION['user_is_admin']) || !$_SESSION['user_is_admin']) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
            exit;
        }
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'ID manquant']);
            exit;
        }
        
        $agenceModel = new Agence();
        $result = $agenceModel->deleteAgence($id);
        
        if ($result === false) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Impossible de supprimer : agence utilisée dans des trajets']);
            exit;
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        exit;
    
    /**
     * Route par défaut - Page d'accueil
     * 
     * Affiche la liste des trajets disponibles
     */
    case 'home':
    default:
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->index();
        break;
}

