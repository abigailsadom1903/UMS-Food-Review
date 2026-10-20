<section class="review-form">
    <h2>Review Form</h2>
    
    <!-- UPDATED: Added method="POST" and action for MVC -->
    <form id="reviewForm" method="POST" action="index.php?action=submitReview">
        <div class="form-group">
            <label for="foodName" class="form-label">Food Name:</label>
            <input type="text" id="foodName" name="food_name" class="form-control" 
                   value="Nasi Goreng Lalap" required>
        </div>
        
        <!-- 
        STUDENT COMMENT:
        I added this restaurant dropdown based on the requirement to include
        UMS-specific restaurants. This uses Bootstrap dropdown component
        which we learned about in class.
        -->
        <div class="form-group">
            <label class="form-label">Restaurant Name:</label>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                        id="restaurantDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Restaurant
                </button>
                <ul class="dropdown-menu" aria-labelledby="restaurantDropdown">
                    <li><a class="dropdown-item" href="#" data-value="Cafe FKJ">Cafe FKJ</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Cafe Tun Fuad">Cafe Tun Fuad</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Cafe TPAR">Cafe TPAR</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Cafe Tun Mustapha">Cafe Tun Mustapha</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Library">Library</a></li>
                    <li><a class="dropdown-item" href="#" data-value="1Borneo">1Borneo</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Kingfisher">Kingfisher</a></li>
                    <li><a class="dropdown-item" href="#" data-value="ODEC">ODEC</a></li>
                </ul>
                <!-- UPDATED: Added name attribute for PHP processing -->
                <input type="hidden" id="selectedRestaurant" name="restaurant_name" required>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Food Category:</label>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                        id="foodCategoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Food Category
                </button>
                <ul class="dropdown-menu" aria-labelledby="foodCategoryDropdown">
                    <li><a class="dropdown-item" href="#" data-value="Vegetarian">Vegetarian</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Non-Vegetarian">Non-Vegetarian</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Vegan">Vegan</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Seafood">Seafood</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Dessert">Dessert</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Beverage">Beverage</a></li>
                </ul>
                <!-- UPDATED: Added name attribute for PHP processing -->
                <input type="hidden" id="selectedCategory" name="food_category" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="diningDate" class="form-label">Dining Date:</label>
            <input type="date" id="diningDate" name="dining_date" class="form-control" 
                   value="<?= date('Y-m-d') ?>" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Rating:</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rating" id="star1" value="1" required>
                    <label class="form-check-label" for="star1">1</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rating" id="star2" value="2">
                    <label class="form-check-label" for="star2">2</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rating" id="star3" value="3" checked>
                    <label class="form-check-label" for="star3">3</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rating" id="star4" value="4">
                    <label class="form-check-label" for="star4">4</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rating" id="star5" value="5">
                    <label class="form-check-label" for="star5">5</label>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="reviewText" class="form-label">Review:</label>
            <textarea id="reviewText" name="review_text" class="form-control" rows="4" required>
The portion was big enough for 2 people but the chicken is so salty.
            </textarea>
        </div>
        
        <button type="submit" class="btn-submit">Submit Review</button>
    </form>
</section>