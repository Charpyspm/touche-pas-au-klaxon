<?php
/**
 * Fichier du modèle Trajet
 * 
 * Gère toutes les opérations liées aux trajets de covoiturage
 * 
 * @package TouchePasAuKlaxon
 * @author Votre Nom
 * @version 1.0
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Classe Trajet
 * 
 * Modèle pour la gestion des trajets de covoiturage
 */
class Trajet {
    /**
     * @var PDO Connexion à la base de données
     */
    private $conn;
    
    /**
     * @var string Nom de la table
     */
    private $table = 'trajets';
    
    /**
     * Constructeur de la classe Trajet
     * 
     * Initialise la connexion à la base de données
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Récupère tous les trajets avec les informations des agences et conducteurs
     * 
     * @return array<int, array<string, mixed>> Tableau contenant tous les trajets triés par date de départ
     */
    public function getAllTrajets() {
        $query = "SELECT 
                    t.*,
                    a_depart.nom as ville_depart,
                    a_arrivee.nom as ville_destination,
                    CONCAT(u.prenom, ' ', u.nom) as conducteur_nom
                  FROM " . $this->table . " t
                  LEFT JOIN agences a_depart ON t.agence_depart_id = a_depart.id
                  LEFT JOIN agences a_arrivee ON t.agence_arrivee_id = a_arrivee.id
                  LEFT JOIN users u ON t.user_id = u.id
                  ORDER BY t.date_depart ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère uniquement les trajets avec des places disponibles
     * 
     * @return array<int, array<string, mixed>> Tableau contenant les trajets disponibles triés par date de départ
     */
    public function getAvailableTrajets() {
        $query = "SELECT 
                    t.*,
                    a_depart.nom as ville_depart,
                    a_arrivee.nom as ville_destination,
                    CONCAT(u.prenom, ' ', u.nom) as conducteur_nom
                  FROM " . $this->table . " t
                  LEFT JOIN agences a_depart ON t.agence_depart_id = a_depart.id
                  LEFT JOIN agences a_arrivee ON t.agence_arrivee_id = a_arrivee.id
                  LEFT JOIN users u ON t.user_id = u.id
                  WHERE t.nombre_places_disponibles > 0
                  ORDER BY t.date_depart ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère un trajet par son ID avec toutes les informations détaillées
     * 
     * @param int $id L'identifiant du trajet
     * @return array<string, mixed>|false Les données complètes du trajet ou false si non trouvé
     */
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
