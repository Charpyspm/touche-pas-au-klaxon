<?php
session_start();

// Routeur simple MVC
$action = $_GET['action'] ?? 'home';

// Router les actions
switch ($action) {
    // Routes d'authentification
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
    
    // Routes des trajets
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
    
    // Route pour récupérer la liste des utilisateurs (admin)
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
    
    // Routes pour la gestion des agences (admin)
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
    
    // Route pour récupérer tous les trajets (admin)
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
    
    // Route par défaut (page d'accueil)
    case 'home':
    default:
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->index();
        break;
}

