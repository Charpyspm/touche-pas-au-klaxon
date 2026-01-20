<?php
/**
 * Tests unitaires pour le modèle Agence
 * 
 * Teste les opérations d'écriture : création, modification, suppression
 * 
 * @package TouchePasAuKlaxon\Tests
 */

require_once __DIR__ . '/DatabaseTestCase.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Agence.php';

class AgenceTest extends DatabaseTestCase
{
    /**
     * @var Agence Instance du modèle Agence
     */
    private $agenceModel;
    
    /**
     * Configuration avant chaque test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->agenceModel = new Agence();
    }
    
    /**
     * Teste la création d'une agence
     */
    public function testCreateAgence(): void
    {
        $result = $this->agenceModel->createAgence('Paris');
        
        $this->assertTrue($result, "La création de l'agence devrait réussir");
        
        // Vérifier que l'agence existe en base
        $agences = $this->agenceModel->getAllAgences();
        $this->assertCount(1, $agences, "Il devrait y avoir 1 agence en base");
        $this->assertEquals('Paris', $agences[0]['nom'], "Le nom de l'agence devrait être Paris");
    }
    
    /**
     * Teste la création de plusieurs agences
     */
    public function testCreateMultipleAgences(): void
    {
        $this->agenceModel->createAgence('Paris');
        $this->agenceModel->createAgence('Lyon');
        $this->agenceModel->createAgence('Marseille');
        
        $agences = $this->agenceModel->getAllAgences();
        $this->assertCount(3, $agences, "Il devrait y avoir 3 agences en base");
    }
    
    /**
     * Teste la modification d'une agence
     */
    public function testUpdateAgence(): void
    {
        // Créer une agence
        $this->agenceModel->createAgence('Paris');
        $agences = $this->agenceModel->getAllAgences();
        $agenceId = $agences[0]['id'];
        
        // Modifier l'agence
        $result = $this->agenceModel->updateAgence($agenceId, 'Paris Centre');
        
        $this->assertTrue($result, "La modification devrait réussir");
        
        // Vérifier la modification
        $agence = $this->agenceModel->getAgenceById($agenceId);
        $this->assertEquals('Paris Centre', $agence['nom'], "Le nom devrait être mis à jour");
    }
    
    /**
     * Teste la suppression d'une agence
     */
    public function testDeleteAgence(): void
    {
        // Créer une agence
        $this->agenceModel->createAgence('Paris');
        $agences = $this->agenceModel->getAllAgences();
        $agenceId = $agences[0]['id'];
        
        // Supprimer l'agence
        $result = $this->agenceModel->deleteAgence($agenceId);
        
        $this->assertTrue($result, "La suppression devrait réussir");
        
        // Vérifier qu'elle n'existe plus
        $agences = $this->agenceModel->getAllAgences();
        $this->assertCount(0, $agences, "Il ne devrait plus y avoir d'agences");
    }
    
    /**
     * Teste qu'on ne peut pas supprimer une agence utilisée dans des trajets
     */
    public function testCannotDeleteAgenceUsedInTrajets(): void
    {
        // Créer des agences
        $this->agenceModel->createAgence('Paris');
        $this->agenceModel->createAgence('Lyon');
        $agences = $this->agenceModel->getAllAgences();
        $agenceParisId = $agences[0]['id'];
        $agenceLyonId = $agences[1]['id'];
        
        // Créer un utilisateur
        self::$pdo->exec("
            INSERT INTO users (nom, prenom, telephone, email, password, is_admin)
            VALUES ('Test', 'User', '0600000000', 'test@test.fr', 'password', 0)
        ");
        $userId = self::$pdo->lastInsertId();
        
        // Créer un trajet utilisant l'agence Paris
        self::$pdo->exec("
            INSERT INTO trajets (agence_depart_id, agence_arrivee_id, date_depart, date_arrivee, 
                                nombre_places_total, nombre_places_disponibles, user_id)
            VALUES ($agenceParisId, $agenceLyonId, '2026-02-01 10:00:00', '2026-02-01 12:00:00', 4, 4, $userId)
        ");
        
        // Tenter de supprimer l'agence Paris
        $result = $this->agenceModel->deleteAgence($agenceParisId);
        
        $this->assertFalse($result, "La suppression devrait échouer car l'agence est utilisée");
        
        // Vérifier que l'agence existe toujours
        $agence = $this->agenceModel->getAgenceById($agenceParisId);
        $this->assertNotFalse($agence, "L'agence devrait toujours exister");
    }
}
