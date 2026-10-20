<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMS Food Review Corner</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main class="container my-4">
        <!-- Success/Error Messages -->
        <?php if (isset($message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <div class="content-wrapper">
            <?php include 'profile.php'; ?>
            <?php include 'review_form.php'; ?>
        </div>
        
        <!-- Display submitted reviews (from database) -->
        <?php if (isset($reviews) && !empty($reviews)): ?>
        <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Recent Reviews</h3>
                <div>
                    <span class="badge bg-primary">Total: <?= count($reviews) ?> reviews</span>
                    <?php if (count($reviews) > 0): 
                        // Calculate average rating
                        $totalRating = 0;
                        foreach ($reviews as $review) {
                            $totalRating += $review['rating'];
                        }
                        $averageRating = round($totalRating / count($reviews), 1);
                    ?>
                    <span class="badge bg-warning ms-2">Avg Rating: <?= $averageRating ?>/5</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row">
                <?php foreach ($reviews as $review): ?>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($review['food_name']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <i class="fas fa-utensils"></i> <?= htmlspecialchars($review['restaurant_name']) ?> • 
                                <span class="badge bg-info"><?= htmlspecialchars($review['food_category']) ?></span>
                            </h6>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars($review['review_text']) ?></p>
                            
                            <div class="mt-auto">
                                <div class="mb-2">
                                    <strong>Rating:</strong>
                                    <div class="d-inline-block ms-2">
                                        <?php 
                                        for ($i = 1; $i <= 5; $i++): 
                                            if ($i <= $review['rating']): 
                                                echo '<span class="text-warning">⭐</span>';
                                            else: 
                                                echo '<span class="text-secondary">☆</span>';
                                            endif;
                                        endfor; 
                                        ?>
                                        <span class="ms-2">(<?= $review['rating'] ?>/5)</span>
                                    </div>
                                </div>
                                
                                <div class="text-muted small mb-3">
                                    <i class="far fa-calendar-alt"></i> Dined on: <?= date('F j, Y', strtotime($review['dining_date'])) ?><br>
                                    <i class="far fa-user"></i> By: <?= htmlspecialchars($review['name']) ?><br>
                                    <i class="far fa-clock"></i> Posted: <?= date('M j, Y g:i A', strtotime($review['created_at'])) ?>
                                </div>
                                
                                <!-- CRUD ACTION BUTTONS -->
                                <div class="d-flex gap-2">
                                    <a href="index.php?action=view&id=<?= $review['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="far fa-eye"></i> View
                                    </a>
                                    <a href="index.php?action=edit&id=<?= $review['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning flex-fill">
                                        <i class="far fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?action=delete&id=<?= $review['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger flex-fill"
                                       onclick="return confirm('Are you sure you want to delete this review?\n\nFood: <?= htmlspecialchars(addslashes($review['food_name'])) ?>\nRestaurant: <?= htmlspecialchars(addslashes($review['restaurant_name'])) ?>')">
                                        <i class="far fa-trash-alt"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Quick Stats -->
            <div class="mt-4 p-3 bg-light rounded">
                <h5>Review Statistics</h5>
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-2">
                            <h4 class="text-primary"><?= count($reviews) ?></h4>
                            <small>Total Reviews</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2">
                            <h4 class="text-success"><?= isset($averageRating) ? $averageRating : '0' ?></h4>
                            <small>Average Rating</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2">
                            <?php
                            // Count restaurants
                            $restaurants = [];
                            foreach ($reviews as $review) {
                                $restaurant = $review['restaurant_name'];
                                if (!isset($restaurants[$restaurant])) {
                                    $restaurants[$restaurant] = 0;
                                }
                                $restaurants[$restaurant]++;
                            }
                            ?>
                            <h4 class="text-info"><?= count($restaurants) ?></h4>
                            <small>Restaurants Reviewed</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2">
                            <?php
                            // Count categories
                            $categories = [];
                            foreach ($reviews as $review) {
                                $category = $review['food_category'];
                                if (!isset($categories[$category])) {
                                    $categories[$category] = 0;
                                }
                                $categories[$category]++;
                            }
                            ?>
                            <h4 class="text-warning"><?= count($categories) ?></h4>
                            <small>Food Categories</small>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <?php elseif (isset($reviews)): ?>
        <!-- No reviews message -->
        <div class="mt-5 text-center py-5 bg-light rounded">
            <h4 class="text-muted">No reviews yet</h4>
            <p class="text-muted">Be the first to submit a food review!</p>
            <div class="mt-3">
                <a href="#reviewForm" class="btn btn-primary">
                    <i class="fas fa-pencil-alt"></i> Write Your First Review
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Quick Actions -->
        <div class="mt-4 d-flex gap-2 justify-content-center">
            <a href="index.php?action=test" class="btn btn-outline-info btn-sm">
                <i class="fas fa-vial"></i> Test CRUD
            </a>
            <a href="index.php?action=stats" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-chart-bar"></i> View Stats
            </a>
            <a href="setup_database.php" class="btn btn-outline-warning btn-sm">
                <i class="fas fa-database"></i> Setup DB
            </a>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <!-- Your Custom JS -->
    <script src="assets/js/script.js"></script>
    
    <script>
    // Add smooth scrolling to review form
    document.addEventListener('DOMContentLoaded', function() {
        const reviewFormLink = document.querySelector('a[href="#reviewForm"]');
        if (reviewFormLink) {
            reviewFormLink.addEventListener('click', function(e) {
                e.preventDefault();
                const reviewForm = document.getElementById('reviewForm');
                if (reviewForm) {
                    reviewForm.scrollIntoView({ behavior: 'smooth' });
                    // Focus on first input
                    const firstInput = reviewForm.querySelector('input, textarea, select');
                    if (firstInput) firstInput.focus();
                }
            });
        }
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
    </script>
</body>
</html>