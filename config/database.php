<?php
/**
 * Fichier de configuration de la base de données
 * 
 * Contient les paramètres de connexion et la classe Database
 * 
 * @package TouchePasAuKlaxon
 * @author Votre Nom
 * @version 1.0
 */

/**
 * Configuration de la base de données
 * 
 * Constantes pour la connexion à MySQL
 */
if (!defined('DB_HOST')) {
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('DB_NAME') ?: 'touche_pas_au_klaxon');
}
if (!defined('DB_USER')) {
    define('DB_USER', getenv('DB_USER') ?: 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', getenv('DB_PASS') ?: '');
}

/**
 * Classe Database
 * 
 * Gère la connexion à la base de données MySQL via PDO
 */
if (!class_exists('Database')) {
    class Database {
        /**
         * @var PDO|null Connexion PDO à la base de données
         */
        private $conn;
        
        /**
         * Établit et retourne une connexion à la base de données
         * 
         * Configure PDO avec :
         * - Mode d'erreur en exception
         * - Charset UTF-8
         * 
         * @return PDO|null Objet PDO de connexion ou null en cas d'erreur
         */
        public function getConnection() {
            $this->conn = null;
            
            try {
                $this->conn = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->exec("set names utf8");
            } catch(PDOException $e) {
                echo "Erreur de connexion : " . $e->getMessage();
            }
            
            return $this->conn;
        }
    }
}
