<?php
/**
 * Classe de base pour les tests
 * 
 * Gère la création et la destruction de la base de données de test
 * 
 * @package TouchePasAuKlaxon\Tests
 */

use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    /**
     * @var PDO Connexion à la base de données
     */
    protected static $pdo;
    
    /**
     * Configuration initiale avant tous les tests
     * 
     * Crée la base de données de test et les tables
     */
    public static function setUpBeforeClass(): void
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbName = getenv('DB_NAME') ?: 'touche_pas_au_klaxon_test';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        
        // Connexion sans base pour la créer
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");
        $pdo->exec("USE $dbName");
        
        // Créer les tables
        self::createTables($pdo);
        
        // Stocker la connexion
        self::$pdo = $pdo;
    }
    
    /**
     * Crée les tables nécessaires pour les tests
     * 
     * @param PDO $pdo Connexion à la base de données
     */
    private static function createTables(PDO $pdo): void
    {
        // Table users
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                telephone VARCHAR(20) NOT NULL,
                email VARCHAR(150) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                is_admin BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Table agences
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS agences (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Table trajets
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS trajets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                agence_depart_id INT NOT NULL,
                agence_arrivee_id INT NOT NULL,
                date_depart DATETIME NOT NULL,
                date_arrivee DATETIME NOT NULL,
                nombre_places_total INT NOT NULL,
                nombre_places_disponibles INT NOT NULL,
                user_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (agence_depart_id) REFERENCES agences(id) ON DELETE CASCADE,
                FOREIGN KEY (agence_arrivee_id) REFERENCES agences(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                CHECK (agence_depart_id != agence_arrivee_id),
                CHECK (date_arrivee > date_depart),
                CHECK (nombre_places_total >= 0),
                CHECK (nombre_places_disponibles >= 0),
                CHECK (nombre_places_disponibles <= nombre_places_total)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }
    
    /**
     * Nettoie les données avant chaque test
     */
    protected function setUp(): void
    {
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        self::$pdo->exec("TRUNCATE TABLE trajets");
        self::$pdo->exec("TRUNCATE TABLE agences");
        self::$pdo->exec("TRUNCATE TABLE users");
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }
    
    /**
     * Nettoyage après tous les tests
     */
    public static function tearDownAfterClass(): void
    {
        $dbName = getenv('DB_NAME') ?: 'touche_pas_au_klaxon_test';
        self::$pdo->exec("DROP DATABASE IF EXISTS $dbName");
        self::$pdo = null;
    }
}
