<?php
/**
 * Fichier du contrôleur des trajets
 * 
 * Gère toutes les opérations CRUD liées aux trajets de covoiturage
 * 
 * @package TouchePasAuKlaxon
 * @author Votre Nom
 * @version 1.0
 */

/**
 * Classe TrajetController
 * 
 * Contrôleur pour la gestion des trajets (création, modification, suppression, affichage)
 */
class TrajetController {
    /**
     * @var Trajet Instance du modèle Trajet
     */
    private $trajetModel;
    
    /**
     * Constructeur de la classe TrajetController
     * 
     * Initialise le modèle Trajet
     */
    public function __construct() {
        require_once __DIR__ . '/../models/Trajet.php';
        $this->trajetModel = new Trajet();
    }
    
    /**
     * Affiche la liste des trajets disponibles sur la page d'accueil
     * 
     * Ne montre que les trajets avec des places disponibles
     * 
     * @return void
     */
    public function index() {
        // Only show trajets with available places on homepage
        $trajets = $this->trajetModel->getAvailableTrajets();
        
        $isConnected = isset($_SESSION['user_id']);
        $userName = '';
        if ($isConnected) {
            $userName = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
        }
        
        $success_message = $_SESSION['success_message'] ?? '';
        $error_message = $_SESSION['error_message'] ?? '';
        unset($_SESSION['success_message'], $_SESSION['error_message']);
        
        require_once __DIR__ . '/../views/home.php';
    }
    
    /**
     * Affiche le formulaire de création d'un trajet
     * 
     * Nécessite une connexion utilisateur
     * 
     * @return void Redirige vers login si non connecté
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
        
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $conn = $database->getConnection();
        
        $queryAgences = "SELECT id, nom FROM agences ORDER BY nom ASC";
        $stmt = $conn->prepare($queryAgences);
        $stmt->execute();
        $agences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $userName = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
        $success_message = $_SESSION['success_message'] ?? '';
        $error_message = $_SESSION['error_message'] ?? '';
        unset($_SESSION['success_message'], $_SESSION['error_message']);
        
        require_once __DIR__ . '/../views/trajet/create.php';
    }
    
    /**
     * Enregistre un nouveau trajet dans la base de données
     * 
     * Valide les données et effectue les contrôles de cohérence :
     * - Vérifie que tous les champs sont remplis
     * - Vérifie que l'agence de départ est différente de l'agence d'arrivée
     * - Vérifie que la date/heure d'arrivée est postérieure à celle de départ
     * 
     * @return void Redirige vers l'accueil si succès, sinon vers le formulaire
     */
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit();
        }
        
        $agence_depart_id = $_POST['agence_depart_id'] ?? '';
        $agence_arrivee_id = $_POST['agence_arrivee_id'] ?? '';
        $date_depart = $_POST['date_depart'] ?? '';
        $heure_depart = $_POST['heure_depart'] ?? '';
        $date_arrivee = $_POST['date_arrivee'] ?? '';
        $heure_arrivee = $_POST['heure_arrivee'] ?? '';
        $nombre_places_total = $_POST['nombre_places_total'] ?? '';
        $user_id = $_SESSION['user_id'];
        
        if (empty($agence_depart_id) || empty($agence_arrivee_id) || empty($date_depart) || 
            empty($heure_depart) || empty($date_arrivee) || empty($heure_arrivee) || 
            !isset($_POST['nombre_places_total']) || $_POST['nombre_places_total'] === '') {
            $_SESSION['error_message'] = "Tous les champs sont obligatoires.";
            header('Location: index.php?action=create');
            exit();
        }
        
        if ($agence_depart_id === $agence_arrivee_id) {
            $_SESSION['error_message'] = "L'agence de départ et d'arrivée doivent être différentes.";
            header('Location: index.php?action=create');
            exit();
        }
        
        $datetime_depart = $date_depart . ' ' . $heure_depart . ':00';
        $datetime_arrivee = $date_arrivee . ' ' . $heure_arrivee . ':00';
        
        if (strtotime($datetime_arrivee) <= strtotime($datetime_depart)) {
            $_SESSION['error_message'] = "La date et l'heure d'arrivée doivent être postérieures à celles de départ.";
            header('Location: index.php?action=create');
            exit();
        }
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $conn = $database->getConnection();
            
            $query = "INSERT INTO trajets 
                      (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, 
                       nombre_places_total, nombre_places_disponibles, user_id) 
                      VALUES 
                      (:agence_depart_id, :agence_arrivee_id, :date_depart, :date_arrivee, 
                       :nombre_places_total, :nombre_places_disponibles, :user_id)";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':agence_depart_id', $agence_depart_id);
            $stmt->bindParam(':agence_arrivee_id', $agence_arrivee_id);
            $stmt->bindParam(':date_depart', $datetime_depart);
            $stmt->bindParam(':date_arrivee', $datetime_arrivee);
            $stmt->bindParam(':nombre_places_total', $nombre_places_total);
            $stmt->bindParam(':nombre_places_disponibles', $nombre_places_total);
            $stmt->bindParam(':user_id', $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Trajet créé avec succès !";
                header('Location: index.php');
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
            header('Location: index.php?action=create');
            exit();
        }
    }
    
    /**
     * Affiche le formulaire de modification d'un trajet
     * 
     * Vérifie que l'utilisateur connecté est le créateur du trajet
     * 
     * @return void Redirige vers l'accueil si le trajet n'existe pas ou n'appartient pas à l'utilisateur
     */
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
        
        $trajet_id = $_GET['id'] ?? null;
        if (!$trajet_id) {
            $_SESSION['error_message'] = "Trajet introuvable.";
            header('Location: index.php');
            exit();
        }
        
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM trajets WHERE id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $trajet_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$trajet) {
            $_SESSION['error_message'] = "Trajet introuvable ou vous n'avez pas la permission de le modifier.";
            header('Location: index.php');
            exit();
        }
        
        $queryAgences = "SELECT id, nom FROM agences ORDER BY nom ASC";
        $stmt = $conn->prepare($queryAgences);
        $stmt->execute();
        $agences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $userName = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
        $error_message = $_SESSION['error_message'] ?? '';
        unset($_SESSION['error_message']);
        
        require_once __DIR__ . '/../views/trajet/edit.php';
    }
    
    /**
     * Met à jour un trajet existant
     * 
     * Valide les données et effectue les mêmes contrôles que lors de la création
     * 
     * @return void Redirige vers l'accueil si succès, sinon vers le formulaire de modification
     */
    public function update() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit();
        }
        
        $trajet_id = $_POST['id'] ?? '';
        $agence_depart_id = $_POST['agence_depart_id'] ?? '';
        $agence_arrivee_id = $_POST['agence_arrivee_id'] ?? '';
        $date_depart = $_POST['date_depart'] ?? '';
        $heure_depart = $_POST['heure_depart'] ?? '';
        $date_arrivee = $_POST['date_arrivee'] ?? '';
        $heure_arrivee = $_POST['heure_arrivee'] ?? '';
        $nombre_places_total = $_POST['nombre_places_total'] ?? '';
        
        if (empty($trajet_id) || empty($agence_depart_id) || empty($agence_arrivee_id) || 
            empty($date_depart) || empty($heure_depart) || empty($date_arrivee) || 
            empty($heure_arrivee) || !isset($_POST['nombre_places_total']) || $_POST['nombre_places_total'] === '') {
            $_SESSION['error_message'] = "Tous les champs sont obligatoires.";
            header('Location: index.php?action=edit&id=' . $trajet_id);
            exit();
        }
        
        if ($agence_depart_id === $agence_arrivee_id) {
            $_SESSION['error_message'] = "L'agence de départ et d'arrivée doivent être différentes.";
            header('Location: index.php?action=edit&id=' . $trajet_id);
            exit();
        }
        
        $datetime_depart = $date_depart . ' ' . $heure_depart . ':00';
        $datetime_arrivee = $date_arrivee . ' ' . $heure_arrivee . ':00';
        
        if (strtotime($datetime_arrivee) <= strtotime($datetime_depart)) {
            $_SESSION['error_message'] = "La date et l'heure d'arrivée doivent être postérieures à celles de départ.";
            header('Location: index.php?action=edit&id=' . $trajet_id);
            exit();
        }
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $conn = $database->getConnection();
            
            $query = "UPDATE trajets 
                      SET agence_depart_id = :agence_depart_id, 
                          agence_arrivee_id = :agence_arrivee_id, 
                          date_depart = :date_depart, 
                          date_arrivee = :date_arrivee, 
                          nombre_places_total = :nombre_places_total,
                          nombre_places_disponibles = :nombre_places_disponibles
                      WHERE id = :id AND user_id = :user_id";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':agence_depart_id', $agence_depart_id);
            $stmt->bindParam(':agence_arrivee_id', $agence_arrivee_id);
            $stmt->bindParam(':date_depart', $datetime_depart);
            $stmt->bindParam(':date_arrivee', $datetime_arrivee);
            $stmt->bindParam(':nombre_places_total', $nombre_places_total);
            $stmt->bindParam(':nombre_places_disponibles', $nombre_places_total);
            $stmt->bindParam(':id', $trajet_id);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Trajet modifié avec succès !";
                header('Location: index.php');
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
            header('Location: index.php?action=edit&id=' . $trajet_id);
            exit();
        }
    }
    
    /**
     * Supprime un trajet
     * 
     * Vérifie que l'utilisateur connecté est le créateur du trajet avant suppression
     * 
     * @return void Redirige vers l'accueil avec un message de succès ou d'erreur
     */
    public function delete() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
        
        $trajet_id = $_GET['id'] ?? null;
        if (!$trajet_id) {
            $_SESSION['error_message'] = "Trajet introuvable.";
            header('Location: index.php');
            exit();
        }
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $conn = $database->getConnection();
            
            $deleteQuery = "DELETE FROM trajets WHERE id = :id AND user_id = :user_id";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $trajet_id);
            $deleteStmt->bindParam(':user_id', $_SESSION['user_id']);
            
            if ($deleteStmt->execute()) {
                $_SESSION['success_message'] = "Trajet supprimé avec succès !";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression du trajet.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
        }
        
        header('Location: index.php');
        exit();
    }
    
    /**
     * Retourne les détails d'un trajet au format JSON
     * 
     * Utilisé pour afficher les informations complètes dans une modal
     * 
     * @return void Affiche un JSON avec les détails du trajet ou une erreur
     */
    public function details() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Non connecté']);
            exit();
        }
        
        $trajet_id = $_GET['id'] ?? null;
        if (!$trajet_id) {
            echo json_encode(['error' => 'Trajet introuvable']);
            exit();
        }
        
        $trajet = $this->trajetModel->getTrajetById($trajet_id);
        
        if (!$trajet) {
            echo json_encode(['error' => 'Trajet introuvable']);
            exit();
        }
        
        echo json_encode([
            'success' => true,
            'auteur' => $trajet['conducteur_prenom'] . ' ' . $trajet['conducteur_nom'],
            'telephone' => $trajet['conducteur_telephone'],
            'email' => $trajet['conducteur_email'],
            'places_total' => $trajet['nombre_places_total'],
            'places_disponibles' => $trajet['nombre_places_disponibles'],
            'depart' => $trajet['ville_depart'],
            'destination' => $trajet['ville_destination'],
            'date_depart' => date('d/m/Y à H:i', strtotime($trajet['date_depart'])),
            'date_arrivee' => date('d/m/Y à H:i', strtotime($trajet['date_arrivee']))
        ]);
    }
}
