<?php
session_start();
require_once '../../src/config/database.php'; // <-- Make sure this is correct!
require_once '../../src/controllers/AdminController.php';

$adminController = new AdminController($pdo); // Pass the PDO object!

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch pending users
$pendingStmt = $pdo->prepare("SELECT * FROM users WHERE status = 'pending'");
$pendingStmt->execute();
$pendingUsers = $pendingStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch approved users
$approvedStmt = $pdo->prepare("SELECT * FROM users WHERE status = 'approved'");
$approvedStmt->execute();
$approvedUsers = $approvedStmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <?php include '../../src/views/partials/header.php'; ?>
    
    <div class="container">
        <h1>Admin Dashboard</h1>
        <h2>Staff Management</h2>


        <h2>Pending Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Sub Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingUsers as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['department']); ?></td>
                        <td><?php echo htmlspecialchars($user['sub_department']); ?></td>
                        <td>
                            <a href="approve_staff.php?id=<?php echo $user['id']; ?>">Approve</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Approved Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Sub Department</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($approvedUsers as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['department']); ?></td>
                        <td><?php echo htmlspecialchars($user['sub_department']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include '../../src/views/partials/footer.php'; ?>
</body>
</html>