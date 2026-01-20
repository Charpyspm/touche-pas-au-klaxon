<?php
/**
 * Tests unitaires pour le contrôleur de trajets
 * 
 * Teste les opérations d'écriture via le modèle Trajet
 * 
 * @package TouchePasAuKlaxon\Tests
 */

require_once __DIR__ . '/DatabaseTestCase.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Trajet.php';
require_once __DIR__ . '/../models/Agence.php';

class TrajetTest extends DatabaseTestCase
{
    /**
     * @var int ID de l'utilisateur de test
     */
    private $userId;
    
    /**
     * @var int ID de l'agence de départ
     */
    private $agenceDepart;
    
    /**
     * @var int ID de l'agence d'arrivée
     */
    private $agenceArrivee;
    
    /**
     * Configuration avant chaque test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur de test
        self::$pdo->exec("
            INSERT INTO users (nom, prenom, telephone, email, password, is_admin)
            VALUES ('Test', 'User', '0600000000', 'test@test.fr', 'password', 0)
        ");
        $this->userId = (int)self::$pdo->lastInsertId();
        
        // Créer des agences de test
        $agenceModel = new Agence();
        $agenceModel->createAgence('Paris');
        $agenceModel->createAgence('Lyon');
        
        $agences = $agenceModel->getAllAgences();
        $this->agenceDepart = (int)$agences[0]['id'];
        $this->agenceArrivee = (int)$agences[1]['id'];
    }
    
    /**
     * Teste la création d'un trajet
     */
    public function testCreateTrajet(): void
    {
        $query = "INSERT INTO trajets 
                  (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, 
                   nombre_places_total, nombre_places_disponibles, user_id) 
                  VALUES 
                  (:agence_depart_id, :agence_arrivee_id, :date_depart, :date_arrivee, 
                   :nombre_places_total, :nombre_places_disponibles, :user_id)";
        
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([
            ':agence_depart_id' => $this->agenceDepart,
            ':agence_arrivee_id' => $this->agenceArrivee,
            ':date_depart' => '2026-02-01 10:00:00',
            ':date_arrivee' => '2026-02-01 12:00:00',
            ':nombre_places_total' => 4,
            ':nombre_places_disponibles' => 4,
            ':user_id' => $this->userId
        ]);
        
        $this->assertTrue($stmt->rowCount() > 0, "Le trajet devrait être créé");
        
        // Vérifier le trajet en base
        $trajetModel = new Trajet();
        $trajets = $trajetModel->getAllTrajets();
        
        $this->assertCount(1, $trajets, "Il devrait y avoir 1 trajet en base");
        $this->assertEquals(4, $trajets[0]['nombre_places_total']);
        $this->assertEquals(4, $trajets[0]['nombre_places_disponibles']);
    }
    
    /**
     * Teste la création d'un trajet avec 0 places
     */
    public function testCreateTrajetWithZeroPlaces(): void
    {
        $query = "INSERT INTO trajets 
                  (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, 
                   nombre_places_total, nombre_places_disponibles, user_id) 
                  VALUES 
                  (:agence_depart_id, :agence_arrivee_id, :date_depart, :date_arrivee, 
                   :nombre_places_total, :nombre_places_disponibles, :user_id)";
        
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([
            ':agence_depart_id' => $this->agenceDepart,
            ':agence_arrivee_id' => $this->agenceArrivee,
            ':date_depart' => '2026-02-01 10:00:00',
            ':date_arrivee' => '2026-02-01 12:00:00',
            ':nombre_places_total' => 0,
            ':nombre_places_disponibles' => 0,
            ':user_id' => $this->userId
        ]);
        
        $this->assertTrue($stmt->rowCount() > 0, "Le trajet avec 0 places devrait être créé");
        
        // Vérifier que getAvailableTrajets() ne le retourne pas
        $trajetModel = new Trajet();
        $trajetsDisponibles = $trajetModel->getAvailableTrajets();
        
        $this->assertCount(0, $trajetsDisponibles, "Le trajet avec 0 places ne devrait pas apparaître dans les trajets disponibles");
        
        // Mais getAllTrajets() devrait le retourner
        $tousTrajets = $trajetModel->getAllTrajets();
        $this->assertCount(1, $tousTrajets, "Le trajet devrait apparaître dans tous les trajets");
    }
    
    /**
     * Teste la modification d'un trajet
     */
    public function testUpdateTrajet(): void
    {
        // Créer un trajet
        self::$pdo->exec("
            INSERT INTO trajets (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, 
                                nombre_places_total, nombre_places_disponibles, user_id)
            VALUES ({$this->agenceDepart}, {$this->agenceArrivee}, '2026-02-01 10:00:00', 
                    '2026-02-01 12:00:00', 4, 4, {$this->userId})
        ");
        $trajetId = self::$pdo->lastInsertId();
        
        // Modifier le nombre de places
        $query = "UPDATE trajets 
                  SET nombre_places_total = :nombre_places_total,
                      nombre_places_disponibles = :nombre_places_disponibles
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = self::$pdo->prepare($query);
        $result = $stmt->execute([
            ':nombre_places_total' => 3,
            ':nombre_places_disponibles' => 3,
            ':id' => $trajetId,
            ':user_id' => $this->userId
        ]);
        
        $this->assertTrue($result, "La modification devrait réussir");
        
        // Vérifier la modification
        $trajetModel = new Trajet();
        $trajet = $trajetModel->getTrajetById($trajetId);
        
        $this->assertEquals(3, $trajet['nombre_places_total']);
        $this->assertEquals(3, $trajet['nombre_places_disponibles']);
    }
    
    /**
     * Teste la suppression d'un trajet
     */
    public function testDeleteTrajet(): void
    {
        // Créer un trajet
        self::$pdo->exec("
            INSERT INTO trajets (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, 
                                nombre_places_total, nombre_places_disponibles, user_id)
            VALUES ({$this->agenceDepart}, {$this->agenceArrivee}, '2026-02-01 10:00:00', 
                    '2026-02-01 12:00:00', 4, 4, {$this->userId})
        ");
        $trajetId = self::$pdo->lastInsertId();
        
        // Supprimer le trajet
        $query = "DELETE FROM trajets WHERE id = :id AND user_id = :user_id";
        $stmt = self::$pdo->prepare($query);
        $result = $stmt->execute([
            ':id' => $trajetId,
            ':user_id' => $this->userId
        ]);
        
        $this->assertTrue($result, "La suppression devrait réussir");
        
        // Vérifier qu'il n'existe plus
        $trajetModel = new Trajet();
        $trajets = $trajetModel->getAllTrajets();
        
        $this->assertCount(0, $trajets, "Il ne devrait plus y avoir de trajets");
    }
    
    /**
     * Teste qu'un utilisateur ne peut pas modifier le trajet d'un autre
     */
    public function testCannotUpdateOtherUserTrajet(): void
    {
        // Créer un autre utilisateur
        self::$pdo->exec("
            INSERT INTO users (nom, prenom, telephone, email, password, is_admin)
            VALUES ('Autre', 'User', '0600000001', 'autre@test.fr', 'password', 0)
        ");
        $autreUserId = self::$pdo->lastInsertId();
        
        // Créer un trajet pour le premier utilisateur
        self::$pdo->exec("
            INSERT INTO trajets (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, 
                                nombre_places_total, nombre_places_disponibles, user_id)
            VALUES ({$this->agenceDepart}, {$this->agenceArrivee}, '2026-02-01 10:00:00', 
                    '2026-02-01 12:00:00', 4, 4, {$this->userId})
        ");
        $trajetId = self::$pdo->lastInsertId();
        
        // Tenter de modifier avec l'autre utilisateur
        $query = "UPDATE trajets 
                  SET nombre_places_total = :nombre_places_total
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([
            ':nombre_places_total' => 2,
            ':id' => $trajetId,
            ':user_id' => $autreUserId
        ]);
        
        $this->assertEquals(0, $stmt->rowCount(), "Aucune ligne ne devrait être modifiée");
        
        // Vérifier que le trajet n'a pas changé
        $trajetModel = new Trajet();
        $trajet = $trajetModel->getTrajetById($trajetId);
        
        $this->assertEquals(4, $trajet['nombre_places_total'], "Le nombre de places ne devrait pas avoir changé");
    }
}
