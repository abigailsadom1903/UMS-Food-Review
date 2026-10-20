<?php
// views/edit_review.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review - UMS Food Review Corner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="review-form">
                    <h2>Edit Review</h2>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?action=updateReview">
                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                        
                        <div class="form-group">
                            <label for="foodName" class="form-label">Food Name:</label>
                            <input type="text" id="foodName" name="food_name" class="form-control" 
                                   value="<?= htmlspecialchars($review['food_name']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="restaurantName" class="form-label">Restaurant Name:</label>
                            <input type="text" id="restaurantName" name="restaurant_name" class="form-control" 
                                   value="<?= htmlspecialchars($review['restaurant_name']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="foodCategory" class="form-label">Food Category:</label>
                            <select id="foodCategory" name="food_category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="Vegetarian" <?= $review['food_category'] == 'Vegetarian' ? 'selected' : '' ?>>Vegetarian</option>
                                <option value="Non-Vegetarian" <?= $review['food_category'] == 'Non-Vegetarian' ? 'selected' : '' ?>>Non-Vegetarian</option>
                                <option value="Vegan" <?= $review['food_category'] == 'Vegan' ? 'selected' : '' ?>>Vegan</option>
                                <option value="Seafood" <?= $review['food_category'] == 'Seafood' ? 'selected' : '' ?>>Seafood</option>
                                <option value="Dessert" <?= $review['food_category'] == 'Dessert' ? 'selected' : '' ?>>Dessert</option>
                                <option value="Beverage" <?= $review['food_category'] == 'Beverage' ? 'selected' : '' ?>>Beverage</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="diningDate" class="form-label">Dining Date:</label>
                            <input type="date" id="diningDate" name="dining_date" class="form-control" 
                                   value="<?= $review['dining_date'] ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Rating:</label>
                            <div class="d-flex gap-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" 
                                           id="star<?= $i ?>" value="<?= $i ?>" 
                                           <?= $review['rating'] == $i ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="star<?= $i ?>"><?= $i ?></label>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="reviewText" class="form-label">Review:</label>
                            <textarea id="reviewText" name="review_text" class="form-control" rows="4" required><?= htmlspecialchars($review['review_text']) ?></textarea>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn-submit">Update Review</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                            <a href="index.php?action=view&id=<?= $review['id'] ?>" class="btn btn-outline-primary">View</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>