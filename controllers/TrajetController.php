<?php
class TrajetController {
    private $trajetModel;
    
    public function __construct() {
        require_once __DIR__ . '/../models/Trajet.php';
        $this->trajetModel = new Trajet();
    }
    
    // Afficher la liste des trajets
    public function index() {
        $trajets = $this->trajetModel->getAllTrajets();
        
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
    
    // Afficher le formulaire de création
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
    
    // Enregistrer un nouveau trajet
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
            empty($nombre_places_total)) {
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
    
    // Afficher le formulaire de modification
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
    
    // Mettre à jour un trajet
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
            empty($heure_arrivee) || empty($nombre_places_total)) {
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
    
    // Supprimer un trajet
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
    
    // Obtenir les détails d'un trajet (API JSON)
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
