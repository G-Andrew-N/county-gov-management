<?php
$to = "staff_email@example.com"; // Replace with the staff member's email
$subject = "Account Approval Notification";
$message = "
<html>
<head>
    <title>Account Approval Notification</title>
</head>
<body>
    <h1>Your Account Has Been Approved!</h1>
    <p>Dear Staff Member,</p>
    <p>We are pleased to inform you that your account has been approved. You can now log in to the County Government Management System and access your dashboard.</p>
    <p>Thank you for your patience during the approval process.</p>
    <p>Best regards,<br>The County Government Team</p>
</body>
</html>
";

// Set content-type header for sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
$headers .= "From: noreply@county.gov" . "\r\n"; // Replace with the appropriate sender email

// Send the email
mail($to, $subject, $message, $headers);
?>