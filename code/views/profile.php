<aside class="profile-section">
    <h2>My Profile</h2>
    <?php
    /*
    STUDENT COMMENT:
    This PHP section demonstrates basic PHP concepts we learned:
    - Arrays and associative arrays
    - echo statements for output
    - foreach loops to iterate through arrays
    - String functions like ucwords() and str_replace()
    
    In MVC version, this data comes from the controller
    */
    
    // For now, using hardcoded data (will be replaced with database data)
    $profile = array(
        'name' => 'ABIGAIL SADOM',
        'matricNo' => 'BI23110260',
        'age' => 21,
        'hometown' => 'SABAH'
    );
    
    // Profile image
    echo '<img src="img/photo.jpg" alt="Profile Picture" class="profile-image">';
    
    echo '<div class="profile-info">';
    foreach($profile as $key => $value) {
        $formattedKey = ucwords(str_replace('_', ' ', $key));
        echo '<div class="profile-item">';
        echo '<span class="profile-label">' . $formattedKey . '</span>';
        echo '<span class="profile-value">' . $value . '</span>';
        echo '</div>';
    }
    echo '</div>';
    ?>
</aside>