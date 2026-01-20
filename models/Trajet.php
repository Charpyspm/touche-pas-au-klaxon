<?php
require_once __DIR__ . '/../config/database.php';

class Trajet {
    private $conn;
    private $table = 'trajets';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAllTrajets() {
        $query = "SELECT 
                    t.*,
                    a_depart.nom as ville_depart,
                    a_arrivee.nom as ville_destination
                  FROM " . $this->table . " t
                  LEFT JOIN agences a_depart ON t.agence_depart_id = a_depart.id
                  LEFT JOIN agences a_arrivee ON t.agence_arrivee_id = a_arrivee.id
                  ORDER BY t.date_depart ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTrajetById($id) {
        $query = "SELECT 
                    t.*,
                    a_depart.nom as ville_depart,
                    a_arrivee.nom as ville_destination,
                    u.prenom as conducteur_prenom,
                    u.nom as conducteur_nom,
                    u.telephone as conducteur_telephone,
                    u.email as conducteur_email
                  FROM " . $this->table . " t
                  LEFT JOIN agences a_depart ON t.agence_depart_id = a_depart.id
                  LEFT JOIN agences a_arrivee ON t.agence_arrivee_id = a_arrivee.id
                  LEFT JOIN users u ON t.user_id = u.id
                  WHERE t.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
