/*
STUDENT COMMENT:
This JavaScript section implements client-side form validation and
dropdown functionality that we learned about in class:
- Event listeners for form submission
- DOM manipulation to get form values
- Basic validation checks
- Working with Bootstrap dropdowns

I added comprehensive validation for all form fields and enhanced
user experience with real-time feedback and error messages.

AI Assistance Declaration:
I used AI suggestions for validation patterns and UX improvements,
but all implementation and customization was done by me based on
class concepts and examples.
*/

document.addEventListener('DOMContentLoaded', function() {
    console.log('UMS Food Review Corner - JavaScript initialized');
    
    // ==================== DROPDOWN FUNCTIONALITY ====================
    
    // Food category dropdown functionality
    const foodCategoryDropdown = document.getElementById('foodCategoryDropdown');
    const foodCategoryMenu = foodCategoryDropdown ? foodCategoryDropdown.nextElementSibling : null;
    const selectedCategoryInput = document.getElementById('selectedCategory');
    
    if (foodCategoryMenu && foodCategoryDropdown) {
        foodCategoryMenu.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedValue = this.getAttribute('data-value');
                foodCategoryDropdown.textContent = selectedValue;
                if (selectedCategoryInput) {
                    selectedCategoryInput.value = selectedValue;
                }
                
                // Visual feedback
                foodCategoryDropdown.classList.remove('btn-outline-secondary');
                foodCategoryDropdown.classList.add('btn-success');
                foodCategoryDropdown.innerHTML = selectedValue + ' <span class="float-end">✓</span>';
                
                console.log('Food category selected:', selectedValue);
            });
        });
    }
    
    // Restaurant dropdown functionality
    const restaurantDropdown = document.getElementById('restaurantDropdown');
    const restaurantMenu = restaurantDropdown ? restaurantDropdown.nextElementSibling : null;
    const selectedRestaurantInput = document.getElementById('selectedRestaurant');
    
    if (restaurantMenu && restaurantDropdown) {
        restaurantMenu.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedValue = this.getAttribute('data-value');
                restaurantDropdown.textContent = selectedValue;
                if (selectedRestaurantInput) {
                    selectedRestaurantInput.value = selectedValue;
                }
                
                // Visual feedback
                restaurantDropdown.classList.remove('btn-outline-secondary');
                restaurantDropdown.classList.add('btn-success');
                restaurantDropdown.innerHTML = selectedValue + ' <span class="float-end">✓</span>';
                
                console.log('Restaurant selected:', selectedValue);
            });
        });
    }
    
    // ==================== FORM VALIDATION ====================
    
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        console.log('Review form found, attaching validation');
        
        // Real-time validation for input fields
        const foodNameInput = document.getElementById('foodName');
        const reviewTextArea = document.getElementById('reviewText');
        
        if (foodNameInput) {
            foodNameInput.addEventListener('input', function() {
                validateField(this, this.value.trim().length > 0, 'Food name is required');
            });
        }
        
        if (reviewTextArea) {
            reviewTextArea.addEventListener('input', function() {
                const isValid = this.value.trim().length >= 10;
                validateField(this, isValid, 'Review must be at least 10 characters');
                
                // Update character counter
                updateCharacterCounter(this);
            });
        }
        
        // Real-time validation for dropdowns
        if (selectedCategoryInput) {
            selectedCategoryInput.addEventListener('change', function() {
                validateDropdown('foodCategoryDropdown', this.value, 'Please select food category');
            });
        }
        
        if (selectedRestaurantInput) {
            selectedRestaurantInput.addEventListener('change', function() {
                validateDropdown('restaurantDropdown', this.value, 'Please select restaurant');
            });
        }
        
        // Real-time validation for rating
        const ratingRadios = document.querySelectorAll('input[name="rating"]');
        ratingRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                validateRating();
            });
        });
        
        // Form submission validation
        reviewForm.addEventListener('submit', function(e) {
            console.log('Form submission attempt');
            
            // Prevent default to validate first
            e.preventDefault();
            
            // Validate all fields
            const isFoodNameValid = validateField(foodNameInput, foodNameInput.value.trim().length > 0, 'Food name is required');
            const isReviewTextValid = validateField(reviewTextArea, reviewTextArea.value.trim().length >= 10, 'Review must be at least 10 characters');
            const isCategoryValid = validateDropdown('foodCategoryDropdown', selectedCategoryInput.value, 'Please select food category');
            const isRestaurantValid = validateDropdown('restaurantDropdown', selectedRestaurantInput.value, 'Please select restaurant');
            const isRatingValid = validateRating();
            
            const diningDateInput = document.getElementById('diningDate');
            let isDateValid = true;
            if (diningDateInput) {
                isDateValid = validateField(diningDateInput, diningDateInput.value, 'Please select dining date');
            }
            
            // Check if all validations passed
            if (isFoodNameValid && isReviewTextValid && isCategoryValid && 
                isRestaurantValid && isRatingValid && isDateValid) {
                
                console.log('All validations passed, submitting form');
                
                // Show loading state
                const submitBtn = this.querySelector('.btn-submit');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after 3 seconds if something goes wrong
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                }
                
                // Remove any existing error alerts
                const existingError = document.querySelector('.validation-error-alert');
                if (existingError) {
                    existingError.remove();
                }
                
                // Show success message
                showAlert('success', 'Submitting your review...', 'Please wait while we save your review.');
                
                // Submit the form after a brief delay for UX
                setTimeout(() => {
                    this.submit();
                }, 500);
                
            } else {
                console.log('Validation failed');
                
                // Show error alert
                showAlert('danger', 'Please fix the errors', 'Some required fields are missing or invalid.');
                
                // Scroll to first error
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    }
    
    // ==================== HELPER FUNCTIONS ====================
    
    /**
     * Validate a form field and show appropriate feedback
     */
    function validateField(field, isValid, errorMessage) {
        if (!field) return false;
        
        const feedbackElement = field.nextElementSibling;
        
        if (isValid) {
            // Valid state
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                feedbackElement.textContent = '';
                feedbackElement.style.display = 'none';
            }
            return true;
        } else {
            // Invalid state
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            
            // Create or update feedback element
            let feedback = field.nextElementSibling;
            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                field.parentNode.appendChild(feedback);
            }
            
            feedback.textContent = errorMessage;
            feedback.style.display = 'block';
            return false;
        }
    }
    
    /**
     * Validate a dropdown field
     */
    function validateDropdown(dropdownId, value, errorMessage) {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return false;
        
        const isValid = value && value !== '';
        
        if (isValid) {
            dropdown.classList.remove('is-invalid');
            dropdown.classList.add('is-valid');
            return true;
        } else {
            dropdown.classList.remove('is-valid');
            dropdown.classList.add('is-invalid');
            
            // Show error message
            let feedback = dropdown.nextElementSibling;
            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                dropdown.parentNode.appendChild(feedback);
            }
            
            feedback.textContent = errorMessage;
            feedback.style.display = 'block';
            return false;
        }
    }
    
    /**
     * Validate rating selection
     */
    function validateRating() {
        const ratingSelected = document.querySelector('input[name="rating"]:checked');
        const ratingContainer = document.querySelector('.form-group:has(input[name="rating"])');
        
        if (ratingSelected) {
            // Valid - remove error styling
            if (ratingContainer) {
                ratingContainer.classList.remove('is-invalid');
                const feedback = ratingContainer.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.remove();
                }
            }
            return true;
        } else {
            // Invalid - add error styling
            if (ratingContainer) {
                ratingContainer.classList.add('is-invalid');
                
                // Add error message
                let feedback = ratingContainer.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    ratingContainer.appendChild(feedback);
                }
                feedback.textContent = 'Please select a rating';
                feedback.style.display = 'block';
            }
            return false;
        }
    }
    
    /**
     * Update character counter for textarea
     */
    function updateCharacterCounter(textarea) {
        let counter = textarea.parentNode.querySelector('.char-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.className = 'char-counter form-text text-end mt-1';
            textarea.parentNode.appendChild(counter);
        }
        
        const length = textarea.value.length;
        counter.textContent = `${length} characters`;
        
        // Color coding
        if (length < 10) {
            counter.style.color = '#dc3545'; // Red
        } else if (length < 50) {
            counter.style.color = '#ffc107'; // Yellow
        } else {
            counter.style.color = '#198754'; // Green
        }
    }
    
    /**
     * Show alert message
     */
    function showAlert(type, title, message) {
        // Remove existing validation alert
        const existingAlert = document.querySelector('.validation-error-alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show validation-error-alert" role="alert">
                <strong>${title}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Insert at the beginning of the form
        if (reviewForm) {
            reviewForm.insertAdjacentHTML('beforebegin', alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.validation-error-alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    }
    
    // ==================== INITIALIZATION ====================
    
    // Set today's date as default for dining date
    const diningDateInput = document.getElementById('diningDate');
    if (diningDateInput) {
        const today = new Date().toISOString().split('T')[0];
        diningDateInput.value = today;
        diningDateInput.max = today; // Prevent future dates
        
        // Add change listener for validation
        diningDateInput.addEventListener('change', function() {
            validateField(this, this.value, 'Please select a valid date');
        });
    }
    
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add help tooltips to form labels
    const formLabels = document.querySelectorAll('.form-label');
    formLabels.forEach(label => {
        const fieldId = label.getAttribute('for');
        if (fieldId) {
            let helpText = '';
            
            switch(fieldId) {
                case 'foodName':
                    helpText = 'Enter the name of the food you want to review';
                    break;
                case 'diningDate':
                    helpText = 'Select the date when you had this food';
                    break;
                case 'reviewText':
                    helpText = 'Share your experience (minimum 10 characters)';
                    break;
            }
            
            if (helpText) {
                label.setAttribute('data-bs-toggle', 'tooltip');
                label.setAttribute('data-bs-placement', 'top');
                label.setAttribute('title', helpText);
                label.style.cursor = 'help';
                label.style.textDecoration = 'underline dotted';
            }
        }
    });
    
    // Initialize character counter for review text
    const reviewText = document.getElementById('reviewText');
    if (reviewText) {
        updateCharacterCounter(reviewText);
    }
    
    console.log('JavaScript initialization complete');
});