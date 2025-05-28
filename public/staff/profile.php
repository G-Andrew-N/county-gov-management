<?php
session_start();
require_once '../../src/controllers/StaffController.php';

$staffController = new StaffController();
$staffId = $_SESSION['staff_id'] ?? null;

if (!$staffId) {
    header('Location: ../login.php');
    exit();
}

$staffProfile = $staffController->getStaffProfile($staffId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Staff Profile</title>
</head>
<body>
    <?php include '../../src/views/partials/header.php'; ?>

    <div class="container">
        <h1>Staff Profile</h1>
        <form action="../../src/controllers/StaffController.php?action=updateProfile" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staffProfile['id']); ?>">
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($staffProfile['email']); ?>" required>
            </div>
            <div>
                <label for="ssn">SSN:</label>
                <input type="text" name="ssn" id="ssn" value="<?php echo htmlspecialchars($staffProfile['ssn']); ?>" required>
            </div>
            <div>
                <label for="department">Department:</label>
                <input type="text" name="department" id="department" value="<?php echo htmlspecialchars($staffProfile['department']); ?>" required>
            </div>
            <div>
                <label for="sub_department">Sub Department:</label>
                <input type="text" name="sub_department" id="sub_department" value="<?php echo htmlspecialchars($staffProfile['sub_department']); ?>" required>
            </div>
            <div>
                <label for="documents">Upload Documents:</label>
                <input type="file" name="documents[]" id="documents" multiple required>
            </div>
            <button type="submit">Update Profile</button>
        </form>
    </div>

    <?php include '../../src/views/partials/footer.php'; ?>
    <script src="../assets/js/main.js"></script>
</body>
</html>