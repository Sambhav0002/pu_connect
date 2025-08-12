<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $userType = $_POST['userType'] ?? '';

    // Validate input
    if (empty($email) || empty($password) || empty($userType)) {
        $_SESSION['error'] = 'Please fill all required fields.';
        header("Location: ../login.html");
        exit;
    }

    // Sanitize input
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $userType = trim($userType);

    // Determine the table based on user type
    $table = ($userType === 'mentor') ? 'mentors' : 'mentees';
    
    try {
        // Prepare and execute query
        $query = "SELECT id, full_name, email, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if user exists
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_interests']=$user['interests'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $userType;
                $_SESSION['user_name'] = $user['full_name'] ?? 'User';
                $_SESSION['user_email'] = $user['email'];
               
                
                // Redirect to appropriate dashboard
                if ($userType === 'mentor') {
                    header("Location: ../mentor-dashboard.php");
                } else {
                    header("Location: ../mentee.php");
                }
                exit;
            } else {
                $_SESSION['error'] = 'Invalid email or password.';
                header("Location: ../login.html");
                exit;
            }
        } else {
            $_SESSION['error'] = 'No account found with that email.';
            header("Location: ../login.html");
            exit;
        }
        
        $stmt->close();
    } catch (Exception $e) {
        // Log the error (in a real application, you'd log to a file)
        error_log($e->getMessage());
        $_SESSION['error'] = 'An error occurred during login. Please try again.';
        header("Location: ../login.html");
        exit;
    }
    
    $conn->close();
} else {
    // Not a POST request, redirect to login
    header("Location: ../login.html");
    exit;
}
?>