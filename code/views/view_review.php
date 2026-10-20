<?php
// views/view_review.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Review - UMS Food Review Corner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Review Details</h2>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Food Name:</h5>
                                <p class="form-control-static"><?= htmlspecialchars($review['food_name']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Restaurant:</h5>
                                <p class="form-control-static"><?= htmlspecialchars($review['restaurant_name']) ?></p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Food Category:</h5>
                                <p class="form-control-static"><?= htmlspecialchars($review['food_category']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Dining Date:</h5>
                                <p class="form-control-static"><?= date('F j, Y', strtotime($review['dining_date'])) ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h5>Rating:</h5>
                            <div class="rating-display">
                                <?php for ($i = 1; $i <= 5; $i++): 
                                    if ($i <= $review['rating']): 
                                        echo '⭐';
                                    else: 
                                        echo '☆';
                                    endif;
                                endfor; ?>
                                <span class="ms-2">(<?= $review['rating'] ?>/5)</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Review:</h5>
                            <div class="review-text p-3 bg-light rounded">
                                <?= nl2br(htmlspecialchars($review['review_text'])) ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h5>Reviewer:</h5>
                            <p class="form-control-static"><?= htmlspecialchars($review['name']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <h5>Submitted:</h5>
                            <p class="form-control-static"><?= date('F j, Y \a\t g:i A', strtotime($review['created_at'])) ?></p>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <a href="index.php" class="btn btn-primary">Back to Home</a>
                            <a href="index.php?action=edit&id=<?= $review['id'] ?>" class="btn btn-warning">Edit Review</a>
                            <a href="index.php?action=delete&id=<?= $review['id'] ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this review?')">Delete Review</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>