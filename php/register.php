<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "pu_connect";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize inputs
$full_name = htmlspecialchars($_POST['full_name']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$student_id = htmlspecialchars($_POST['student_id']);
$department = htmlspecialchars($_POST['department']);
$phone = htmlspecialchars($_POST['phone']);
$role = htmlspecialchars($_POST['role']);
$interests = htmlspecialchars($_POST['interests']);
$terms = isset($_POST['terms']) ? 1 : 0;
$experience = isset($_POST['experience']) ? htmlspecialchars($_POST['experience']) : null;
$expertise = isset($_POST['expertise']) ? implode(",", array_map('htmlspecialchars', $_POST['expertise'])) : null;

// Check if passwords match
if ($_POST['password'] !== $_POST['confirm_password']) {
    die("Passwords do not match.");
}

// Insert into database
$sql = "INSERT INTO users (full_name, email, password, student_id, department, phone, role, expertise, experience, interests, terms_agreed)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssi", $full_name, $email, $password, $student_id, $department, $phone, $role, $expertise, $experience, $interests, $terms);

if ($stmt->execute()) {
    // Registration successful - now send email
    
    // Load Composer's autoloader (if using Composer)
   // require 'vendor/autoload.php';
    
    // Or manually require PHPMailer files (if not using Composer)
    // Import PHPMailer classes into the global namespac
    require '../PHPMailer/PHPMailer.php';
     require '../PHPMailer/SMTP.php';
    require '../PHPMailer/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'maniketbharti07@gmail.com'; // SMTP username
        $mail->Password   = 'dnow myyr fkar wysc';    // SMTP password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('no-reply@puconnect.edu', 'PU Connect');
        $mail->addAddress($email, $full_name); // Add a recipient
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to PU Connect!';
        
        $mail->Body    = "
            <h1>Welcome to PU Connect, $full_name!</h1>
            <p>Thank you for registering as a $role on our platform.</p>
            <p>Here are your registration details:</p>
            <ul>
                <li><strong>Name:</strong> $full_name</li>
                <li><strong>Student ID:</strong> $student_id</li>
                <li><strong>Department:</strong> $department</li>
                <li><strong>Role:</strong> $role</li>
            </ul>
            <p>You can now login to your account and start connecting with other students.</p>
            <p>Best regards,<br>The PU Connect Team</p>
        ";
        
        $mail->AltBody = "Welcome to PU Connect, $full_name!\n\nThank you for registering as a $role.\n\nYou can now login to your account.";
        
        $mail->send();
        
        echo "<script>
        alert('Registration successful! A welcome email has been sent to $email.');
        window.location.href = '../login.html';
        </script>";
    } catch (Exception $e) {
        // Email failed but registration was successful
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        echo "<script>
        alert('Registration successful! However, the welcome email could not be sent.');
        window.location.href = '../login.html';
        </script>";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>