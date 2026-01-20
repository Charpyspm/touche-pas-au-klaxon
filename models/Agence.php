<?php
require_once __DIR__ . '/../config/database.php';

class Agence {
    private $conn;
    private $table = 'agences';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAllAgences() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nom ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAgenceById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createAgence($nom) {
        $query = "INSERT INTO " . $this->table . " (nom) VALUES (:nom)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        
        return $stmt->execute();
    }
    
    public function updateAgence($id, $nom) {
        $query = "UPDATE " . $this->table . " SET nom = :nom WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $nom);
        
        return $stmt->execute();
    }
    
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
