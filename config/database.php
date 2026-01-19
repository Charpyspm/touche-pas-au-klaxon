<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'touche_pas_au_klaxon');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private $conn;
    
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
