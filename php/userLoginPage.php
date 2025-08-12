<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $userType = $_POST['userType'] ?? '';

    // Debug received data
    echo "<pre>Received data:\n";
    echo "Email: $email\n";
    echo "User Type: $userType\n";
    echo "Password Length: " . strlen($password) . "</pre>";

    // Sanitize input
    $email = trim($email);
    $userType = trim($userType);

    // Check for empty fields
    if (empty($email) || empty($password) || empty($userType)) {
        die("<script>alert('Please fill all required fields.'); window.location.href='login.html';</script>");
    }

    // Prepare query based on user type
    $table = ($userType === 'mentor') ? 'mentors' : 'mentees';
    $query = "SELECT * FROM $table WHERE email = ?";
    
    echo "<pre>Executing query: $query with email: $email</pre>"; // Debug
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $num_rows = $result->num_rows;
    
    echo "<pre>Found $num_rows users</pre>"; // Debug
    
    // Check if user exists
    if ($num_rows === 1) {
        $user = $result->fetch_assoc();
        echo "<pre>User data:\n" . print_r($user, true) . "</pre>"; // Debug
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $userType;
            $_SESSION['user_name'] = $user['full_name'] ?? 'User';
            
            echo "<pre>Login successful! Redirecting...</pre>"; // Debug
            
            // Redirect to respective dashboard
            if ($userType === 'mentor') {
                header("Location: ../mentor-dashboard.php");
            } else {
                header("Location: mentee.php");
            }
            exit;
        } else {
            die("<script>alert('Invalid password.'); window.location.href='login.html';</script>");
        }
    } else {
        die("<script>alert('No account found with that email.'); window.location.href='login.html';</script>");
    }

    $stmt->close();
    $conn->close();
}
?>