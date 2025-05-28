<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: ../login.php');
    exit();
}

// Include database connection
require_once '../../src/config/database.php';

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch any updates or notifications for the staff
$updates_query = "SELECT * FROM updates WHERE user_id = :user_id ORDER BY created_at DESC";
$updates_stmt = $pdo->prepare($updates_query);
$updates_stmt->bindParam(':user_id', $user_id);
$updates_stmt->execute();
$updates = $updates_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Staff Dashboard</title>
</head>
<body>
    <?php include '../../src/views/partials/header.php'; ?>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        <h2>Your Information</h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Department: <?php echo htmlspecialchars($user['department']); ?></p>
        <p>Sub Department: <?php echo htmlspecialchars($user['sub_department']); ?></p>

        <h2>Updates</h2>
        <ul>
            <?php foreach ($updates as $update): ?>
                <li><?php echo htmlspecialchars($update['message']); ?> - <?php echo htmlspecialchars($update['created_at']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php include '../../src/views/partials/footer.php'; ?>
    <script src="../assets/js/main.js"></script>
</body>
</html>