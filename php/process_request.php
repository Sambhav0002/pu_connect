<?php
require_once 'functions.php';

// In a real app, you would have proper authentication
// For this example, we'll use a hardcoded mentee ID
$menteeId = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mentorId = $_POST['mentor_id'] ?? 0;
    $message = $_POST['message'] ?? '';
    
    if ($mentorId && $message) {
        if (sendMentorRequest($mentorId, $menteeId, $message)) {
            // Redirect back with success message
            header("Location: mentee.php?success=1");
            exit();
        } else {
            die("Error sending mentor request");
        }
    } else {
        die("Invalid request data");
    }
} else {
    header("Location: mentee.php");
    exit();
}
?>