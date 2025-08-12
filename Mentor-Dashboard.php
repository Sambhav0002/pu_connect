<?php 
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "pu_connect");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Count all mentees
$sql = "SELECT COUNT(*) as total_mentees FROM users WHERE role = 'mentee'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$mentee_count = $row['total_mentees'];

// Count mentees for specific mentor
if (isset($_SESSION['user_id'])) {
    $mentor_id = $_SESSION['user_id'];
    $stmt = $mysqli->prepare("SELECT COUNT(*) as my_mentees FROM mentor_mentee WHERE mentor_id = ?");
    $stmt->bind_param("i", $mentor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $my_mentee_count = $row['my_mentees'];
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard | PU Connect</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --warning: #f72585;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            color: white;
            height: 100vh;
            position: fixed;
            width: 280px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 1rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 5px;
            margin: 5px 0;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
        
        .profile-card {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            margin-bottom: 1rem;
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border: none;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }
        
        /* Meeting Card Styles */
        .meeting-card {
            border-left: 4px solid var(--accent);
            transition: all 0.3s;
            margin-bottom: 1rem;
        }
        
        .meeting-card:hover {
            transform: translateX(5px);
        }
        
        .zoom-btn {
            background-color: #2D8CFF;
            color: white;
            border: none;
        }
        
        .zoom-btn:hover {
            background-color: #1a7ae6;
            color: white;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                overflow: hidden;
            }
            
            .sidebar .nav-text,
            .sidebar .profile-info,
            .sidebar-brand span {
                display: none;
            }
            
            .sidebar .nav-link {
                text-align: center;
                padding: 0.75rem 0.5rem;
            }
            
            .sidebar .nav-link i {
                margin-right: 0;
                font-size: 1.25rem;
            }
            
            .main-content {
                margin-left: 80px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-nav {
                display: flex;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .nav-link {
                display: inline-block;
            }
        }

        /* Logout button styling */
        .logout-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            width: 100%;
            text-align: left;
            color: rgba(255,255,255,0.8);
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-center">
            <img src="assets/images/logo.png" alt="Logo" class="me-2" height="50px" width="50px">
            <span class="fs-4">PU Connect</span>
        </div>
        
        <div class="profile-card">
            <img src="assets/images/avtar.jpg" alt="Profile" class="profile-img">
            <h5 class="mb-1 text-white"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h5>
            <small class="text-white-50">Mentor</small>
        </div>
        
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="mentor_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="my_mentees.php">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">My Mentees</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="meetings.php">
                    <i class="fas fa-video"></i>
                    <span class="nav-text">Meetings</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="resources.php">
                    <i class="fas fa-book"></i>
                    <span class="nav-text">Resources</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reports.php">
                    <i class="fas fa-chart-line"></i>
                    <span class="nav-text">Reports</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link" href="profile.php">
                    <i class="fas fa-user"></i>
                    <span class="nav-text">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="settings.php">
                    <i class="fas fa-cog"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <form action="logout.php" method="post">
                    <button type="submit" class="nav-link logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Mentor Dashboard</h2>
            <div>
                <button class="btn btn-light position-relative me-2">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </button>
                <button class="btn btn-light position-relative">
                    <i class="fas fa-envelope"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        5
                    </span>
                </button>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row mb-4 g-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="mb-0"><?php echo $mentee_count; ?></h3>
                    <p class="text-muted mb-0">Active Mentees</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon text-success">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3 class="mb-0">5</h3>
                    <p class="text-muted mb-0">Upcoming Meetings</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="mb-0">3</h3>
                    <p class="text-muted mb-0">Pending Requests</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon text-info">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="mb-0">24</h3>
                    <p class="text-muted mb-0">Resources Shared</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Upcoming Meetings -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upcoming Meetings</h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleMeetingModal">
                            <i class="fas fa-plus me-1"></i> Schedule
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <!-- Meeting 1 with Zoom -->
                            <div class="list-group-item border-0 mb-3 rounded meeting-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Sambhav</h6>
                                        <small class="text-muted d-block mb-2">
                                            <i class="far fa-clock me-1"></i>
                                            Today, 2:00 PM - 3:00 PM
                                        </small>
                                        <p class="small mb-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Research paper discussion and project guidance
                                        </p>
                                        <a href="https://zoom.us/j/123456789" target="_blank" class="btn btn-sm zoom-btn">
                                            <i class="fas fa-video me-1"></i> Join Zoom Meeting
                                        </a>
                                        <small class="d-block text-muted mt-1">
                                            Meeting ID: 123 456 7890 | Passcode: 1234
                                        </small>
                                    </div>
                                    <span class="badge bg-primary">Virtual</span>
                                </div>
                            </div>
                            
                            <!-- Meeting 2 In-person -->
                            <div class="list-group-item border-0 mb-3 rounded meeting-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Saurav</h6>
                                        <small class="text-muted d-block mb-2">
                                            <i class="far fa-clock me-1"></i>
                                            Tomorrow, 11:00 AM - 12:00 PM
                                        </small>
                                        <p class="small mb-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Thesis proposal review
                                        </p>
                                        <p class="small mb-0">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Computer Science Dept, Room 205
                                        </p>
                                    </div>
                                    <span class="badge bg-success">In-Person</span>
                                </div>
                            </div>
                            
                            <!-- Meeting 3 with Zoom -->
                            <div class="list-group-item border-0 mb-3 rounded meeting-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Maniket</h6>
                                        <small class="text-muted d-block mb-2">
                                            <i class="far fa-clock me-1"></i>
                                            Friday, 3:30 PM - 4:30 PM
                                        </small>
                                        <p class="small mb-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Career guidance session
                                        </p>
                                        <a href="https://zoom.us/j/987654321" target="_blank" class="btn btn-sm zoom-btn">
                                            <i class="fas fa-video me-1"></i> Join Zoom Meeting
                                        </a>
                                        <small class="d-block text-muted mt-1">
                                            Meeting ID: 987 654 3210 | Passcode: 5678
                                        </small>
                                    </div>
                                    <span class="badge bg-primary">Virtual</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mentee Requests -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Mentee Requests</h5>
                        <span class="badge bg-warning">3 New</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Maniket</strong>
                                            <div class="small text-muted">MSc Computer Science</div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success me-1">Accept</button>
                                            <button class="btn btn-sm btn-outline-danger">Decline</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Sambhav</strong>
                                            <div class="small text-muted">B.Tech IT</div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success me-1">Accept</button>
                                            <button class="btn btn-sm btn-outline-danger">Decline</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Saurav</strong>
                                            <div class="small text-muted">PhD Data Science</div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success me-1">Accept</button>
                                            <button class="btn btn-sm btn-outline-danger">Decline</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- My Mentees -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Mentees</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <img src="https://via.placeholder.com/50" alt="Mentee" class="rounded-circle me-3">
                                    <div>
                                        <h6 class="mb-0">Grace Lee</h6>
                                        <small class="text-muted">B.Tech CSE</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <img src="https://via.placeholder.com/50" alt="Mentee" class="rounded-circle me-3">
                                    <div>
                                        <h6 class="mb-0">Henry Taylor</h6>
                                        <small class="text-muted">MSc AI</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <img src="https://via.placeholder.com/50" alt="Mentee" class="rounded-circle me-3">
                                    <div>
                                        <h6 class="mb-0">Ivy Martinez</h6>
                                        <small class="text-muted">PhD Cybersecurity</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <img src="https://via.placeholder.com/50" alt="Mentee" class="rounded-circle me-3">
                                    <div>
                                        <h6 class="mb-0">Jack Robinson</h6>
                                        <small class="text-muted">B.Tech IT</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Resources -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Resources</h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadResourceModal">
                            <i class="fas fa-plus me-1"></i> Add
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action border-0 mb-2 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Research Methodology Guide</h6>
                                        <small class="text-muted">
                                            <span class="badge bg-light text-dark me-2">PDF</span>
                                            Oct 15, 2023
                                        </small>
                                    </div>
                                    <i class="fas fa-download text-primary"></i>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action border-0 mb-2 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Academic Writing Workshop</h6>
                                        <small class="text-muted">
                                            <span class="badge bg-light text-dark me-2">Video</span>
                                            Sep 28, 2023
                                        </small>
                                    </div>
                                    <i class="fas fa-download text-primary"></i>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action border-0 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Machine Learning Basics</h6>
                                        <small class="text-muted">
                                            <span class="badge bg-light text-dark me-2">PPT</span>
                                            Nov 2, 2023
                                        </small>
                                    </div>
                                    <i class="fas fa-download text-primary"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Meeting Modal -->
    <div class="modal fade" id="scheduleMeetingModal" tabindex="-1" aria-labelledby="scheduleMeetingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleMeetingModalLabel">Schedule New Meeting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="menteeSelect" class="form-label">Mentee</label>
                            <select class="form-select" id="menteeSelect">
                                <option selected>Select Mentee</option>
                                <option>Alice Johnson</option>
                                <option>Bob Williams</option>
                                <option>Charlie Brown</option>
                                <option>David Miller</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="meetingDateTime" class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control" id="meetingDateTime">
                        </div>
                        <div class="mb-3">
                            <label for="meetingType" class="form-label">Meeting Type</label>
                            <select class="form-select" id="meetingType">
                                <option selected>Virtual (Zoom)</option>
                                <option>In-Person</option>
                                <option>Phone Call</option>
                            </select>
                        </div>
                        <div class="mb-3" id="zoomDetails">
                            <label for="zoomLink" class="form-label">Zoom Meeting Link</label>
                            <input type="url" class="form-control" id="zoomLink" placeholder="https://zoom.us/j/123456789">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="zoomId" class="form-label">Meeting ID</label>
                                    <input type="text" class="form-control" id="zoomId" placeholder="123 456 7890">
                                </div>
                                <div class="col-md-6">
                                    <label for="zoomPasscode" class="form-label">Passcode</label>
                                    <input type="text" class="form-control" id="zoomPasscode" placeholder="Optional">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 d-none" id="locationDetails">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" placeholder="Building name, room number">
                        </div>
                        <div class="mb-3">
                            <label for="agenda" class="form-label">Agenda</label>
                            <textarea class="form-control" id="agenda" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Schedule Meeting</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Resource Modal -->
    <div class="modal fade" id="uploadResourceModal" tabindex="-1" aria-labelledby="uploadResourceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadResourceModalLabel">Upload New Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="resourceTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="resourceTitle">
                        </div>
                        <div class="mb-3">
                            <label for="resourceDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="resourceDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="resourceType" class="form-label">Resource Type</label>
                            <select class="form-select" id="resourceType">
                                <option selected>PDF</option>
                                <option>Video</option>
                                <option>Presentation</option>
                                <option>Document</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="resourceFile" class="form-label">File</label>
                            <input class="form-control" type="file" id="resourceFile">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="shareWithAll">
                                <label class="form-check-label" for="shareWithAll">
                                    Share with all my mentees
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Upload Resource</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Toggle meeting type fields
        document.getElementById('meetingType').addEventListener('change', function() {
            const meetingType = this.value;
            const zoomDetails = document.getElementById('zoomDetails');
            const locationDetails = document.getElementById('locationDetails');
            
            if (meetingType === 'Virtual (Zoom)') {
                zoomDetails.classList.remove('d-none');
                locationDetails.classList.add('d-none');
            } else if (meetingType === 'In-Person') {
                zoomDetails.classList.add('d-none');
                locationDetails.classList.remove('d-none');
            } else {
                zoomDetails.classList.add('d-none');
                locationDetails.classList.add('d-none');
            }
        });
        
        // Responsive sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>