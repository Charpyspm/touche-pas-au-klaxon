<?php
/**
 * Fichier du contrôleur d'authentification
 * 
 * Gère toutes les opérations liées à l'authentification des utilisateurs
 * 
 * @package TouchePasAuKlaxon
 * @author Votre Nom
 * @version 1.0
 */

/**
 * Classe AuthController
 * 
 * Contrôleur pour la gestion de l'authentification (connexion/déconnexion)
 */
class AuthController {
    /**
     * @var User Instance du modèle User
     */
    private $userModel;
    
    /**
     * Constructeur de la classe AuthController
     * 
     * Initialise le modèle User
     */
    public function __construct() {
        require_once __DIR__ . '/../models/User.php';
        $this->userModel = new User();
    }
    
    /**
     * Affiche le formulaire de connexion
     * 
     * @return void
     */
    public function showLoginForm() {
        $error_message = $_SESSION['error_message'] ?? '';
        unset($_SESSION['error_message']);
        
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Traite la soumission du formulaire de connexion
     * 
     * Vérifie les identifiants de l'utilisateur et crée une session si valides
     * 
     * @return void Redirige vers l'accueil ou le formulaire de connexion
     */
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
    
    /**
     * Crée une session utilisateur
     * 
     * Stocke les informations de l'utilisateur dans la session
     * 
     * @param array<string, mixed> $user Les données de l'utilisateur
     * @return void
     */
    private function createSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_is_admin'] = $user['is_admin'];
    }
    
    /**
     * Déconnecte l'utilisateur
     * 
     * Détruit la session et redirige vers la page d'accueil
     * 
     * @return void
     */
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
