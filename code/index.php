<?php
// AI TOOL USED: ChatGPT (OpenAI) for guidance on MVC structure and prepared statements.
// Student: ABIGAIL SADOM
// Matric: BI23110260

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Simple router
$action = $_GET['action'] ?? 'home';

// Load controller - CHECK YOUR ACTUAL FOLDER NAME
if (is_dir('controllers')) {
    require_once 'controllers/ReviewController.php';
} elseif (is_dir('controller')) {
    require_once 'controller/ReviewController.php';
} else {
    die("❌ ERROR: Neither 'controllers/' nor 'controller/' folder found!");
}

$controller = new ReviewController();

// Execute action
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo "Action not found.";
    // Fallback to home
    $controller->home();
}
?>