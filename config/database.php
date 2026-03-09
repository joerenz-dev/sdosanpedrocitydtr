<?php
/**
 * Database Configuration
 * SDO San Pedro City DTR System
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sdosanpedrocity_dtr');

// Database connection class
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;
    
    /**
     * Create database connection
     */
    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->dbname,
                $this->user,
                $this->pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
        
        return $this->conn;
    }
}

/**
 * Get database connection
 */
function getConnection() {
    $database = new Database();
    return $database->connect();
}
?>
