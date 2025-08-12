<?php
session_start();
require 'conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$appId = $_GET['id'] ?? 0;
// Get application data
// Create user account
// Update application status
// Send approval email

echo json_encode(['success' => true]);
?>