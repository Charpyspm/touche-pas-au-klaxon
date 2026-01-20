<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        require_once __DIR__ . '/../models/User.php';
        $this->userModel = new User();
    }
    
    // Afficher le formulaire de connexion
    public function showLoginForm() {
        $error_message = $_SESSION['error_message'] ?? '';
        unset($_SESSION['error_message']);
        
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    // Traiter la connexion
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit();
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (!empty($email) && !empty($password)) {
            $user = $this->userModel->getUserByEmail($email);
            
            if ($user) {
                // Vérifier le mot de passe
                if (!empty($user['password']) && password_verify($password, $user['password'])) {
                    // Mot de passe correct
                    $this->createSession($user);
                    header('Location: index.php');
                    exit();
                } else {
                    $_SESSION['error_message'] = "Email ou mot de passe incorrect.";
                    header('Location: index.php?action=login');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = "Email ou mot de passe incorrect.";
                header('Location: index.php?action=login');
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Veuillez entrer votre email et votre mot de passe.";
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // Créer la session utilisateur
    private function createSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_is_admin'] = $user['is_admin'];
    }
    
    // Déconnexion
    public function logout() {
        $_SESSION = array();
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        session_destroy();
        
        header('Location: index.php');
        exit();
    }
}
