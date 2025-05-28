<?php
session_start();
require_once '../../src/config/database.php';

// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Update status in users table
    $stmt = $pdo->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
    $stmt->execute([$userId]);
}

header('Location: dashboard.php');
exit();
?>