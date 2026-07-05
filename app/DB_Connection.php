<?php
require_once 'DB_Config.php';

class DB_Connection {
    private $pdo;
    private static $instance = null;
    
    private function __construct() {
        try {
            $dsn = "pgsql:host=" . DB_Config::HOST . ";dbname=" . DB_Config::NAME;

            $this->pdo = new PDO($dsn, DB_Config::USER, DB_Config::PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,    
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  
                PDO::ATTR_EMULATE_PREPARES => false,     
                PDO::ATTR_PERSISTENT => false                
            ]);
        
            $this->pdo->exec("SET NAMES 'UTF8'");
        } catch (PDOException $e) {
            error_log("Соединение с базой данных прервано: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getPDO() {
        return $this->pdo;
    }
}
?>