<?php
class ReviewModel {
    private $conn;

    public function __construct() {
        require_once 'config/database.php';
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // CREATE - Add new review
    public function createReview($user_id, $food_name, $restaurant_name, $food_category, $dining_date, $rating, $review_text) {
        $stmt = $this->conn->prepare("
            INSERT INTO reviews 
            (user_id, food_name, restaurant_name, food_category, dining_date, rating, review_text) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$user_id, $food_name, $restaurant_name, $food_category, $dining_date, $rating, $review_text]);
    }

    // READ - Get all reviews
    public function getAllReviews() {
        $stmt = $this->conn->query("
            SELECT r.*, u.name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            ORDER BY r.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ - Get single review by ID
    public function getReviewById($id) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE - Edit existing review
    public function updateReview($id, $food_name, $restaurant_name, $food_category, $dining_date, $rating, $review_text) {
        $stmt = $this->conn->prepare("
            UPDATE reviews 
            SET food_name = ?, restaurant_name = ?, food_category = ?, 
                dining_date = ?, rating = ?, review_text = ?, 
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$food_name, $restaurant_name, $food_category, $dining_date, $rating, $review_text, $id]);
    }

    // DELETE - Remove review
    public function deleteReview($id) {
        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Count total reviews
    public function countReviews() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM reviews");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Get reviews by user ID
    public function getReviewsByUser($user_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM reviews 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search reviews by food name or restaurant
    public function searchReviews($keyword) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.food_name LIKE ? OR r.restaurant_name LIKE ? 
            ORDER BY r.created_at DESC
        ");
        $searchTerm = "%" . $keyword . "%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get average rating
    public function getAverageRating() {
        $stmt = $this->conn->query("SELECT AVG(rating) as avg_rating FROM reviews");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($result['avg_rating'], 1);
    }
}
?>