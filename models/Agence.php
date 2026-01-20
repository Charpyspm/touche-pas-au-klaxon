<?php
/**
 * Fichier du modèle Agence
 * 
 * Gère toutes les opérations CRUD liées aux agences
 * 
 * @package TouchePasAuKlaxon
 * @author Votre Nom
 * @version 1.0
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Classe Agence
 * 
 * Modèle pour la gestion des agences (lieux de covoiturage)
 */
class Agence {
    /**
     * @var PDO Connexion à la base de données
     */
    private $conn;
    
    /**
     * @var string Nom de la table
     */
    private $table = 'agences';
    
    /**
     * Constructeur de la classe Agence
     * 
     * Initialise la connexion à la base de données
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Récupère toutes les agences
     * 
     * @return array<int, array<string, mixed>> Tableau contenant toutes les agences triées par nom
     */
    public function getAllAgences() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nom ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère une agence par son ID
     * 
     * @param int $id L'identifiant de l'agence
     * @return array<string, mixed>|false Les données de l'agence ou false si non trouvée
     */
    public function getAgenceById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crée une nouvelle agence
     * 
     * @param string $nom Le nom de l'agence
     * @return bool True si la création a réussi, false sinon
     */
    public function createAgence($nom) {
        $query = "INSERT INTO " . $this->table . " (nom) VALUES (:nom)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        
        return $stmt->execute();
    }
    
    /**
     * Met à jour une agence existante
     * 
     * @param int $id L'identifiant de l'agence
     * @param string $nom Le nouveau nom de l'agence
     * @return bool True si la mise à jour a réussi, false sinon
     */
    public function updateAgence($id, $nom) {
        $query = "UPDATE " . $this->table . " SET nom = :nom WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $nom);
        
        return $stmt->execute();
    }
    
    /**
     * Supprime une agence
     * 
     * Vérifie d'abord si l'agence est utilisée dans des trajets.
     * Si oui, la suppression est refusée.
     * 
     * @param int $id L'identifiant de l'agence
     * @return bool True si la suppression a réussi, false si l'agence est utilisée ou en cas d'erreur
     */
    public function deleteAgence($id) {
        // Vérifier si l'agence est utilisée dans des trajets
        $checkQuery = "SELECT COUNT(*) as count FROM trajets 
                       WHERE agence_depart_id = :id OR agence_arrivee_id = :id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':id', $id);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return false; // Agence utilisée, ne pas supprimer
        }
        
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
