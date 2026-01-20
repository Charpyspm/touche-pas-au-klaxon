<?php
/**
 * Fichier du modèle User
 * 
 * Gère toutes les opérations liées aux utilisateurs
 * 
 * @package TouchePasAuKlaxon
 * @author Votre Nom
 * @version 1.0
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Classe User
 * 
 * Modèle pour la gestion des utilisateurs
 */
class User {
    /**
     * @var PDO Connexion à la base de données
     */
    private $conn;
    
    /**
     * @var string Nom de la table
     */
    private $table = 'users';
    
    /**
     * Constructeur de la classe User
     * 
     * Initialise la connexion à la base de données
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Récupère un utilisateur par son email
     * 
     * @param string $email L'adresse email de l'utilisateur
     * @return array<string, mixed>|false Les données de l'utilisateur ou false si non trouvé
     */
    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère tous les utilisateurs
     * 
     * @return array<int, array<string, mixed>> Tableau contenant tous les utilisateurs
     */
    public function getAllUsers() {
        $query = "SELECT id, nom, prenom, email, telephone, is_admin FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
