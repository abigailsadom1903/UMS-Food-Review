<?php
class Database {
    private $host = "localhost";
    private $db_name = "food_review_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $e) {
            // Show error in development, generic message in production
            $error_message = "Database connection failed";
            
            if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
                $error_message .= ": " . $e->getMessage();
            }
            
            // Log error for debugging
            error_log("Database Error: " . $e->getMessage());
            
            // Display user-friendly error
            die("
                <div style='padding: 20px; margin: 20px; border: 2px solid #ff6b6b; border-radius: 10px; background: #fff5f5;'>
                    <h2 style='color: #ff6b6b;'>⚠️ Database Connection Error</h2>
                    <p>Unable to connect to the database. Please check:</p>
                    <ul>
                        <li>Is MySQL running in XAMPP/WAMP?</li>
                        <li>Is the database 'food_review_db' created?</li>
                        <li>Are the credentials correct in config/database.php?</li>
                    </ul>
                    " . ($_SERVER['SERVER_NAME'] == 'localhost' ? 
                        "<p><strong>Technical Details:</strong> " . htmlspecialchars($e->getMessage()) . "</p>" : 
                        "<p>Please contact the administrator if the problem persists.</p>") . "
                    <p><a href='setup_database.php' style='color: #ff6b6b; font-weight: bold;'>Click here to run database setup</a></p>
                </div>
            ");
        }
        return $this->conn;
    }

    /**
     * Test if database connection is working
     */
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            $stmt = $conn->query("SELECT 1 as test");
            $result = $stmt->fetch();
            return isset($result['test']) && $result['test'] == 1;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Create database if it doesn't exist
     */
    public function createDatabaseIfNotExists() {
        try {
            // Connect without specifying database
            $temp_conn = new PDO(
                "mysql:host=" . $this->host,
                $this->username,
                $this->password
            );
            $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS `$this->db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Select the database
            $temp_conn->exec("USE `$this->db_name`");
            
            return true;
        } catch (PDOException $e) {
            error_log("Create Database Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Execute SQL file to create tables
     */
    public function executeSQLFile($file_path) {
        try {
            $conn = $this->getConnection();
            
            if (!file_exists($file_path)) {
                throw new Exception("SQL file not found: $file_path");
            }
            
            $sql = file_get_contents($file_path);
            
            // Split SQL by semicolon (simple method)
            $queries = explode(';', $sql);
            
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $conn->exec($query);
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Execute SQL Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Backup database to SQL file
     */
    public function backupDatabase($backup_path) {
        try {
            // This is a simple backup method
            // In production, use mysqldump command
            $conn = $this->getConnection();
            
            $tables = [];
            $stmt = $conn->query("SHOW TABLES");
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            
            $backup_sql = "-- UMS Food Review Corner Database Backup\n";
            $backup_sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $backup_sql .= "-- Database: $this->db_name\n\n";
            
            foreach ($tables as $table) {
                // Drop table if exists
                $backup_sql .= "DROP TABLE IF EXISTS `$table`;\n";
                
                // Create table
                $stmt = $conn->query("SHOW CREATE TABLE `$table`");
                $create_table = $stmt->fetch();
                $backup_sql .= $create_table['Create Table'] . ";\n\n";
                
                // Insert data
                $stmt = $conn->query("SELECT * FROM `$table`");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($rows)) {
                    $columns = array_keys($rows[0]);
                    $backup_sql .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES \n";
                    
                    $values = [];
                    foreach ($rows as $row) {
                        $row_values = array_map(function($value) use ($conn) {
                            if ($value === null) return 'NULL';
                            return $conn->quote($value);
                        }, $row);
                        
                        $values[] = "(" . implode(', ', $row_values) . ")";
                    }
                    
                    $backup_sql .= implode(",\n", $values) . ";\n\n";
                }
            }
            
            file_put_contents($backup_path, $backup_sql);
            return true;
            
        } catch (Exception $e) {
            error_log("Backup Error: " . $e->getMessage());
            return false;
        }
    }
}
?>