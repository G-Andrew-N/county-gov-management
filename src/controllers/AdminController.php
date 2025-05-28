<?php

class AdminController {
    private $db;

    // Accept PDO object from outside
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function approveStaff($staffId) {
        // Approve in staff table
        $stmt = $this->db->prepare("UPDATE staff SET status = 'approved' WHERE id = ?");
        $stmt->execute([$staffId]);

        // Get the staff email to update users table
        $emailStmt = $this->db->prepare("SELECT email FROM staff WHERE id = ?");
        $emailStmt->execute([$staffId]);
        $staff = $emailStmt->fetch(PDO::FETCH_ASSOC);

        if ($staff && isset($staff['email'])) {
            // Approve in users table
            $userStmt = $this->db->prepare("UPDATE users SET status = 'approved' WHERE email = ?");
            $userStmt->execute([$staff['email']]);
        }

        return true;
    }

    public function viewStaff() {
        $stmt = $this->db->prepare("SELECT * FROM staff");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function viewDashboard() {
         $stmt = $this->db->prepare("SELECT status, COUNT(*) as count FROM staff GROUP BY status");
        $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingStaff() {
        $stmt = $this->db->prepare("SELECT id, email, department, sub_department FROM staff WHERE status = 'pending'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  

    public function handleRequest($requestUri, $requestMethod) {
        // Simple example routing logic
        if ($requestUri === '/admin/dashboard.php') {
            require_once __DIR__ . '/../../public/admin/dashboard.php';
        } elseif ($requestUri === '/admin/approve_staff.php') {
            require_once __DIR__ . '/../../public/admin/approve_staff.php';
        } else {
            http_response_code(404);
            echo 'Admin page not found';
        }
    }

    // Additional admin functionalities can be added here
}