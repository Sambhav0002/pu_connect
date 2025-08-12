<?php
/**
 * PU Connect - Admin Panel (Single File Version)
 * Includes: Login, Dashboard, Logout, and Database Operations
 */

// =============================================
// CONFIGURATION
// =============================================
$config = [
    'db_host' => 'localhost',
    'db_name' => 'pu_connect',
    'db_user' => 'root',
    'db_pass' => '',
    'site_name' => 'PU Connect',
    'admin_email' => 'admin@puconnect.edu'
];

// =============================================
// SESSION AND SECURITY SETUP
// =============================================
session_start();
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');

// Simple CSRF protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// =============================================
// DATABASE CONNECTION
// =============================================
try {
    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8",
        $config['db_user'],
        $config['db_pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// =============================================
// HELPER FUNCTIONS
// =============================================
function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: ?action=login');
        exit;
    }
}

function format_date($date) {
    if (empty($date)) return 'Never';
    return date('M j, Y g:i A', strtotime($date));
}

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// =============================================
// ACTION HANDLER
// =============================================
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'login':
        handle_login();
        break;
    case 'logout':
        handle_logout();
        break;
    case 'dashboard':
    default:
        handle_dashboard();
        break;
}

// =============================================
// ACTION FUNCTIONS
// =============================================
function handle_login() {
    global $pdo;
    
    $error = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error = 'Invalid form submission';
        } else {
            $username = sanitize_input($_POST['username']);
            $password = $_POST['password']; // Don't sanitize passwords
            
            if (empty($username) || empty($password)) {
                $error = 'Please enter both username and password';
            } else {
                $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
                $stmt->execute([$username]);
                $admin = $stmt->fetch();
                
                if ($admin && password_verify($password, $admin['password'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    
                    // Update last login
                    $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?")
                        ->execute([$admin['id']]);
                    
                    header('Location: ?action=dashboard');
                    exit;
                } else {
                    $error = 'Invalid username or password';
                }
            }
        }
    }
    
    render_login_page($error);
}

function handle_logout() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header('Location: ?action=login');
    exit;
}

function handle_dashboard() {
    global $pdo;
    require_login();
    
    $admin_id = $_SESSION['admin_id'];
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch();
    
    render_dashboard($admin);
}

// =============================================
// RENDER FUNCTIONS
// =============================================
function render_login_page($error = '') {
    global $config;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $config['site_name'] ?> - Admin Login</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            :root {
                --primary: #3498db;
                --secondary: #2c3e50;
                --danger: #e74c3c;
                --light: #ecf0f1;
                --dark: #34495e;
            }
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f5f5f5;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .login-container {
                background: white;
                width: 100%;
                max-width: 400px;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            .login-container h2 {
                color: var(--secondary);
                margin-bottom: 1.5rem;
            }
            .form-group {
                margin-bottom: 1.5rem;
                text-align: left;
            }
            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                color: var(--dark);
                font-weight: 600;
            }
            .form-group input {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 1rem;
                box-sizing: border-box;
            }
            .btn {
                display: inline-block;
                width: 100%;
                padding: 0.75rem;
                background: var(--primary);
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                text-decoration: none;
                font-size: 1rem;
            }
            .btn:hover {
                opacity: 0.9;
            }
            .alert {
                padding: 0.75rem;
                margin-bottom: 1rem;
                border-radius: 4px;
            }
            .alert-danger {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2><i class="fas fa-lock"></i> Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" action="?action=login">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-key"></i> Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

function render_dashboard($admin) {
    global $config;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $config['site_name'] ?> - Admin Dashboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            :root {
                --primary: #3498db;
                --secondary: #2c3e50;
                --danger: #e74c3c;
                --light: #ecf0f1;
                --dark: #34495e;
            }
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f5f5f5;
            }
            .admin-container {
                display: flex;
                min-height: 100vh;
            }
            .sidebar {
                width: 250px;
                background: var(--secondary);
                color: white;
            }
            .sidebar-header {
                padding: 1.5rem;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .sidebar-nav ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .sidebar-nav li a {
                display: block;
                padding: 0.75rem 1.5rem;
                color: white;
                text-decoration: none;
                transition: background 0.3s;
            }
            .sidebar-nav li a:hover {
                background: rgba(255,255,255,0.1);
            }
            .sidebar-nav li a i {
                width: 20px;
                margin-right: 10px;
                text-align: center;
            }
            .main-content {
                flex: 1;
                padding: 2rem;
            }
            .main-header {
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #ddd;
            }
            .stat-card {
                background: white;
                border-radius: 8px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                display: flex;
                align-items: center;
            }
            .stat-icon {
                width: 60px;
                height: 60px;
                background: var(--primary);
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 1.5rem;
                font-size: 1.5rem;
            }
        </style>
    </head>
    <body>
        <div class="admin-container">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3><?= $config['site_name'] ?></h3>
                    <p>Admin Panel</p>
                </div>
                
                <nav class="sidebar-nav">
                    <ul>
                        <li>
                            <a href="?action=dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-users"></i> Manage Users
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-calendar-alt"></i> Events
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        <li>
                            <a href="?action=logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="main-content">
                <header class="main-header">
                    <h1>Welcome, <?= $admin['full_name'] ?? $admin['username'] ?></h1>
                    <p>Last login: <?= format_date($admin['last_login']) ?></p>
                </header>
                
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Users</h3>
                            <p>1,254</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Upcoming Events</h3>
                            <p>12</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>