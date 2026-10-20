<?php
// controllers/ReviewController.php
// AI TOOL USED: ChatGPT (OpenAI) for MVC structure and CRUD implementation guidance
// Student: ABIGAIL SADOM
// Matric: BI23110260

require_once 'models/UserModel.php';
require_once 'models/ReviewModel.php';

class ReviewController {
    private $userModel;
    private $reviewModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
    }
    
    // ==================== CRUD ACTIONS ====================
    
    /**
     * HOME - Display all reviews (READ)
     */
    public function home() {
        // Get user profile
        $user = $this->userModel->getUserByMatric('BI23110260');
        
        // Get all reviews
        $reviews = $this->reviewModel->getAllReviews();
        
        // Show success/error messages
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        // Load the view
        require_once 'views/home.php';
    }
    
    /**
     * CREATE - Submit new review
     */
    public function submitReview() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get user
            $user = $this->userModel->getUserByMatric('BI23110260');
            
            if (!$user) {
                $_SESSION['error'] = "User not found in database.";
                header('Location: index.php');
                exit;
            }
            
            $user_id = $user['id'];
            
            // Sanitize and validate inputs
            $food_name = htmlspecialchars(trim($_POST['food_name'] ?? ''));
            $restaurant_name = htmlspecialchars(trim($_POST['restaurant_name'] ?? ''));
            $food_category = htmlspecialchars(trim($_POST['food_category'] ?? ''));
            $dining_date = $_POST['dining_date'] ?? date('Y-m-d');
            $rating = (int)($_POST['rating'] ?? 0);
            $review_text = htmlspecialchars(trim($_POST['review_text'] ?? ''));
            
            // Validation
            $errors = [];
            if (empty($food_name)) $errors[] = "Food name is required.";
            if (empty($restaurant_name)) $errors[] = "Restaurant name is required.";
            if (empty($food_category)) $errors[] = "Food category is required.";
            if ($rating < 1 || $rating > 5) $errors[] = "Rating must be between 1 and 5.";
            if (empty($review_text)) $errors[] = "Review text is required.";
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode(" ", $errors);
            } else {
                // CREATE operation
                try {
                    $success = $this->reviewModel->createReview(
                        $user_id, $food_name, $restaurant_name, 
                        $food_category, $dining_date, $rating, $review_text
                    );
                    
                    if ($success) {
                        $_SESSION['message'] = "✅ Review submitted successfully!";
                    } else {
                        $_SESSION['error'] = "❌ Failed to save review.";
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                }
            }
            
            header('Location: index.php');
            exit;
        }
        
        // If not POST, redirect to home
        header('Location: index.php');
        exit;
    }
    
    /**
     * READ - View single review
     */
    public function view() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = "Review ID is required.";
            header('Location: index.php');
            exit;
        }
        
        $review = $this->reviewModel->getReviewById($id);
        
        if (!$review) {
            $_SESSION['error'] = "Review not found.";
            header('Location: index.php');
            exit;
        }
        
        require_once 'views/view_review.php';
    }
    
    /**
     * UPDATE - Show edit form
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = "Review ID is required.";
            header('Location: index.php');
            exit;
        }
        
        $review = $this->reviewModel->getReviewById($id);
        
        if (!$review) {
            $_SESSION['error'] = "Review not found.";
            header('Location: index.php');
            exit;
        }
        
        require_once 'views/edit_review.php';
    }
    
    /**
     * UPDATE - Process edit form
     */
    public function updateReview() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['review_id'] ?? 0;
            
            if (!$id) {
                $_SESSION['error'] = "Review ID is required.";
                header('Location: index.php');
                exit;
            }
            
            // Sanitize and validate
            $food_name = htmlspecialchars(trim($_POST['food_name'] ?? ''));
            $restaurant_name = htmlspecialchars(trim($_POST['restaurant_name'] ?? ''));
            $food_category = htmlspecialchars(trim($_POST['food_category'] ?? ''));
            $dining_date = $_POST['dining_date'] ?? date('Y-m-d');
            $rating = (int)($_POST['rating'] ?? 0);
            $review_text = htmlspecialchars(trim($_POST['review_text'] ?? ''));
            
            // Validation
            $errors = [];
            if (empty($food_name)) $errors[] = "Food name is required.";
            if (empty($restaurant_name)) $errors[] = "Restaurant name is required.";
            if (empty($food_category)) $errors[] = "Food category is required.";
            if ($rating < 1 || $rating > 5) $errors[] = "Rating must be between 1 and 5.";
            if (empty($review_text)) $errors[] = "Review text is required.";
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode(" ", $errors);
                header("Location: index.php?action=edit&id=$id");
                exit;
            }
            
            // UPDATE operation
            try {
                $success = $this->reviewModel->updateReview(
                    $id, $food_name, $restaurant_name, 
                    $food_category, $dining_date, $rating, $review_text
                );
                
                if ($success) {
                    $_SESSION['message'] = "✅ Review updated successfully!";
                } else {
                    $_SESSION['error'] = "❌ Failed to update review.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Database error: " . $e->getMessage();
            }
            
            header('Location: index.php');
            exit;
        }
        
        header('Location: index.php');
        exit;
    }
    
    /**
     * DELETE - Remove review
     */
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = "Review ID is required.";
            header('Location: index.php');
            exit;
        }
        
        // DELETE operation
        try {
            $success = $this->reviewModel->deleteReview($id);
            
            if ($success) {
                $_SESSION['message'] = "✅ Review deleted successfully!";
            } else {
                $_SESSION['error'] = "❌ Failed to delete review.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
        
        header('Location: index.php');
        exit;
    }
    
    // ==================== ADDITIONAL ACTIONS ====================
    
    /**
     * Search reviews
     */
    public function search() {
        $keyword = $_GET['q'] ?? '';
        $reviews = $this->reviewModel->searchReviews($keyword);
        $user = $this->userModel->getUserByMatric('BI23110260');
        
        require_once 'views/search_results.php';
    }
    
    /**
     * Statistics page
     */
    public function stats() {
        $total_reviews = $this->reviewModel->countReviews();
        $avg_rating = $this->reviewModel->getAverageRating();
        $user = $this->userModel->getUserByMatric('BI23110260');
        $user_reviews = $user ? $this->reviewModel->getReviewsByUser($user['id']) : [];
        
        require_once 'views/stats.php';
    }
    
    /**
     * Test page for debugging
     */
    public function test() {
        echo "<h2>CRUD Test Page</h2>";
        
        // Test database connection
        try {
            $user = $this->userModel->getUserByMatric('BI23110260');
            
            if ($user) {
                echo "<div class='alert alert-success'>✅ User found: " . htmlspecialchars($user['name']) . "</div>";
                
                // Test reviews
                $reviews = $this->reviewModel->getAllReviews();
                echo "<div class='alert alert-info'>✅ Found " . count($reviews) . " reviews</div>";
                
                // List available actions
                echo "<h3>Available CRUD Actions:</h3>";
                echo "<ul>";
                echo "<li><a href='index.php'>home() - View all reviews</a></li>";
                echo "<li><a href='index.php?action=view&id=1'>view() - View single review</a></li>";
                echo "<li><a href='index.php?action=edit&id=1'>edit() - Edit review</a></li>";
                echo "<li><a href='index.php?action=delete&id=1'>delete() - Delete review</a></li>";
                echo "<li><a href='index.php?action=stats'>stats() - Statistics</a></li>";
                echo "</ul>";
                
                // List recent reviews with IDs
                if (!empty($reviews)) {
                    echo "<h3>Recent Reviews (for testing):</h3>";
                    echo "<ul>";
                    foreach (array_slice($reviews, 0, 5) as $review) {
                        echo "<li>";
                        echo "ID: {$review['id']} - " . htmlspecialchars($review['food_name']);
                        echo " [<a href='index.php?action=view&id={$review['id']}'>View</a>]";
                        echo " [<a href='index.php?action=edit&id={$review['id']}'>Edit</a>]";
                        echo " [<a href='index.php?action=delete&id={$review['id']}' onclick='return confirm(\"Delete?\")'>Delete</a>]";
                        echo "</li>";
                    }
                    echo "</ul>";
                }
                
            } else {
                echo "<div class='alert alert-danger'>❌ User not found. Run database setup first.</div>";
                echo "<a href='setup_database.php' class='btn btn-warning'>Setup Database</a>";
            }
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
        
        echo "<p><a href='index.php' class='btn btn-primary'>Back to Home</a></p>";
    }
    
    /**
     * Create test review (for testing only)
     */
    public function createTest() {
        $user = $this->userModel->getUserByMatric('BI23110260');
        
        if ($user) {
            $result = $this->reviewModel->createReview(
                $user['id'],
                'Test Food ' . time(),
                'Test Restaurant',
                'Test Category',
                date('Y-m-d'),
                4,
                'This is a test review created at ' . date('H:i:s')
            );
            
            if ($result) {
                $_SESSION['message'] = "✅ Test review created successfully!";
            } else {
                $_SESSION['error'] = "❌ Test review creation failed.";
            }
        } else {
            $_SESSION['error'] = "User not found for test.";
        }
        
        header('Location: index.php?action=test');
        exit;
    }
    
    /**
     * Fallback for undefined actions
     */
    public function notFound() {
        http_response_code(404);
        echo "<h2>404 - Action Not Found</h2>";
        echo "<p>The requested action does not exist.</p>";
        echo "<p><a href='index.php'>Go to Home Page</a></p>";
    }
}
?>