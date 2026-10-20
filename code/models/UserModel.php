<?php
class UserModel {
    private $conn;

    public function __construct() {
        require_once 'config/database.php';
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // CREATE - Add new user
    public function createUser($matric_no, $name, $age, $hometown, $profile_image) {
        $stmt = $this->conn->prepare("
            INSERT INTO users (matric_no, name, age, hometown, profile_image) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$matric_no, $name, $age, $hometown, $profile_image]);
    }

    // READ - Get user by matric number
    public function getUserByMatric($matric_no) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE matric_no = ?");
        $stmt->execute([$matric_no]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // READ - Get user by ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // READ - Get all users
    public function getAllUsers() {
        $stmt = $this->conn->query("SELECT * FROM users ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // UPDATE - Edit user profile
    public function updateUser($id, $name, $age, $hometown, $profile_image) {
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET name = ?, age = ?, hometown = ?, profile_image = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$name, $age, $hometown, $profile_image, $id]);
    }

    // UPDATE - Edit user without changing image
    public function updateUserBasic($id, $name, $age, $hometown) {
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET name = ?, age = ?, hometown = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$name, $age, $hometown, $id]);
    }

    // DELETE - Remove user
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Check if matric number exists
    public function matricExists($matric_no, $exclude_id = null) {
        if ($exclude_id) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE matric_no = ? AND id != ?");
            $stmt->execute([$matric_no, $exclude_id]);
        } else {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE matric_no = ?");
            $stmt->execute([$matric_no]);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Count total users
    public function countUsers() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM users");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Search users by name or matric
    public function searchUsers($keyword) {
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE name LIKE ? OR matric_no LIKE ? 
            ORDER BY name ASC
        ");
        $searchTerm = "%" . $keyword . "%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get user with their reviews count
    public function getUserWithReviewCount($user_id) {
        $stmt = $this->conn->prepare("
            SELECT u.*, COUNT(r.id) as review_count
            FROM users u
            LEFT JOIN reviews r ON u.id = r.user_id
            WHERE u.id = ?
            GROUP BY u.id
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get top reviewers (users with most reviews)
    public function getTopReviewers($limit = 5) {
        $stmt = $this->conn->query("
            SELECT u.*, COUNT(r.id) as review_count
            FROM users u
            LEFT JOIN reviews r ON u.id = r.user_id
            GROUP BY u.id
            ORDER BY review_count DESC, u.name ASC
            LIMIT $limit
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verify user credentials (if you add passwords later)
    public function verifyUser($matric_no, $password) {
        // For future implementation if adding login system
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE matric_no = ? AND password = ?
        ");
        $stmt->execute([$matric_no, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user's last login time
    public function updateLastLogin($user_id) {
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET last_login = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        return $stmt->execute([$user_id]);
    }
}
?>