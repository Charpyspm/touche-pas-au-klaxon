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
    
    // Route par défaut (page d'accueil)
    case 'home':
    default:
        require_once __DIR__ . '/controllers/TrajetController.php';
        $controller = new TrajetController();
        $controller->index();
        break;
}

