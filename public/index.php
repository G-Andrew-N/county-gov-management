<?php
// Entry point for the County Government Management System

// Autoload dependencies
//require_once '../vendor/autoload.php';

// Load configuration
require_once '../src/config/database.php';

// Start session
session_start();

// Routing logic
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Define routes
if ($requestUri === '/' || $requestUri === '/index.php') {
    // Load the home page or redirect
    header('Location: login.php');
    exit();
} elseif ($requestUri === '/login.php') {
    require_once 'login.php';
} elseif ($requestUri === '/register.php') {
    require_once 'register.php';
} elseif (strpos($requestUri, '/admin') === 0) {
    require_once '../src/controllers/AdminController.php';
    // Handle admin routes
    $adminController = new AdminController($pdo);
    $adminController->handleRequest($requestUri, $requestMethod);
} elseif (strpos($requestUri, '/staff') === 0) {
    require_once '../src/controllers/StaffController.php';
    // Handle staff routes
    $staffController = new StaffController();
    $staffController->handleRequest($requestUri, $requestMethod);
} else {
    // 404 Not Found
    http_response_code(404);
    echo '404 Not Found';
}
?>