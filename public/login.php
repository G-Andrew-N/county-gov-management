<?php
require_once '../src/config/database.php';
session_start();

$error = '';

// Check if the user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit();
    } else {
        header('Location: staff/dashboard.php');
        exit();
    }
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check users table only
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'pending') {
            $error = "Account waiting for approval.";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['roles']; // roles field: 'admin' or 'staff'
            if ($user['roles'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: staff/dashboard.php');
            }
            exit();
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
   
</head>
<body>
    <div class="login-wrapper">
        <div class="login-left">
            <div class="logo">NY</div>
            <h1>Welcome!</h1>
            <p>Nyandarua County Government Approval System</p>
        </div>
        <div class="login-right">
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="login-form-row">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="login-form-row">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>