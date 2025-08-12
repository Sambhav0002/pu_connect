<?php
session_start();
require 'conn.php'; // Database connection

/* Check if user is admin and logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
   header("Location: adminLogin.php");
   exit;
}*/
// Get counts for dashboard
$mentor_count = $conn->query("SELECT COUNT(*) FROM users WHERE role='mentor'")->fetch_row()[0];
$mentee_count = $conn->query("SELECT COUNT(*) FROM users WHERE role='mentee'")->fetch_row()[0];
$pending_mentors = $conn->query("SELECT COUNT(*) FROM mentor_requests WHERE status='pending'")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | PU Connect</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .dashboard-card {
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar bg-dark collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="../assets/images/logo-white.png" alt="PU Connect" width="80%">
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#mentor-approval" data-bs-toggle="tab">
                                <i class="bi bi-person-check me-2"></i>Mentor Approvals
                                <?php if($pending_mentors > 0): ?>
                                    <span class="badge bg-danger rounded-pill ms-1"><?= $pending_mentors ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#mentor-management" data-bs-toggle="tab">
                                <i class="bi bi-people me-2"></i>Manage Mentors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#mentee-management" data-bs-toggle="tab">
                                <i class="bi bi-person me-2"></i>Manage Mentees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pairings" data-bs-toggle="tab">
                                <i class="bi bi-shuffle me-2"></i>Manage Pairings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#disputes" data-bs-toggle="tab">
                                <i class="bi bi-exclamation-triangle me-2"></i>Disputes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#content" data-bs-toggle="tab">
                                <i class="bi bi-file-earmark-text me-2"></i>Content Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#announcements" data-bs-toggle="tab">
                                <i class="bi bi-megaphone me-2"></i>Announcements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#settings" data-bs-toggle="tab">
                                <i class="bi bi-gear me-2"></i>Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> 
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary dashboard-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Mentors</h5>
                                        <h2 class="card-text"><?= $mentor_count ?></h2>
                                        <a href="#mentor-management" class="text-white stretched-link" data-bs-toggle="tab"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-success dashboard-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Mentees</h5>
                                        <h2 class="card-text"><?= $mentee_count ?></h2>
                                        <a href="#mentee-management" class="text-white stretched-link" data-bs-toggle="tab"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning dashboard-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Pending Approvals</h5>
                                        <h2 class="card-text"><?= $pending_mentors ?></h2>
                                        <a href="#mentor-approval" class="text-white stretched-link" data-bs-toggle="tab"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-danger dashboard-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Active Disputes</h5>
                                        <h2 class="card-text">5</h2>
                                        <a href="#disputes" class="text-white stretched-link" data-bs-toggle="tab"></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5>Recent Mentor Applications</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Department</th>
                                                        <th>Interest</th>
                                                        <th>Applied On</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $apps = $conn->query("SELECT * FROM users where role='mentor'");
                                                    while($row = $apps->fetch_assoc()):
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                                                        <td><?= htmlspecialchars($row['department']) ?></td>
                                                        <td><?= htmlspecialchars($row['interests']) ?></td>
                                                        <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-success">Approve</button>
                                                            <button class="btn btn-sm btn-danger">Reject</button>
                                                        </td>
                                                    </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="#mentor-approval" class="btn btn-primary" data-bs-toggle="tab">
                                                <i class="bi bi-person-check me-2"></i>Review Applications
                                            </a>
                                            <a href="#pairings" class="btn btn-success" data-bs-toggle="tab">
                                                <i class="bi bi-shuffle me-2"></i>Create Pairings
                                            </a>
                                            <a href="#announcements" class="btn btn-info" data-bs-toggle="tab">
                                                <i class="bi bi-megaphone me-2"></i>Post Announcement
                                            </a>
                                            <a href="#content" class="btn btn-warning" data-bs-toggle="tab">
                                                <i class="bi bi-file-earmark-text me-2"></i>Update Content
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mentor Approval Tab -->
                    <div class="tab-pane fade" id="mentor-approval">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Mentor Applications</h5>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary">Pending</button>
                                    <button class="btn btn-sm btn-outline-secondary">Approved</button>
                                    <button class="btn btn-sm btn-outline-secondary">Rejected</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Department</th>
                                                <th>Expertise</th>
                                                <th>Experience</th>
                                                <th>Applied On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $apps = $conn->query("SELECT * FROM mentor_applications WHERE status='pending' ORDER BY applied_on DESC");
                                            while($row = $apps->fetch_assoc()):
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td><?= htmlspecialchars($row['department']) ?></td>
                                                <td><?= htmlspecialchars($row['expertise']) ?></td>
                                                <td><?= htmlspecialchars($row['experience']) ?> years</td>
                                                <td><?= date('M d, Y', strtotime($row['applied_on'])) ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-success approve-mentor" data-id="<?= $row['id'] ?>">Approve</button>
                                                        <button class="btn btn-outline-danger reject-mentor" data-id="<?= $row['id'] ?>">Reject</button>
                                                        <button class="btn btn-outline-primary view-application" data-id="<?= $row['id'] ?>">View</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Management Tab -->
                    <div class="tab-pane fade" id="content">
                        <div class="card">
                            <div class="card-header">
                                <h5>Website Content Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item list-group-item-action active">Homepage</a>
                                            <a href="#" class="list-group-item list-group-item-action">About Page</a>
                                            <a href="#" class="list-group-item list-group-item-action">Mentorship Program</a>
                                            <a href="#" class="list-group-item list-group-item-action">Resources</a>
                                            <a href="#" class="list-group-item list-group-item-action">FAQ</a>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <form>
                                            <div class="mb-3">
                                                <label for="pageTitle" class="form-label">Page Title</label>
                                                <input type="text" class="form-control" id="pageTitle" value="Welcome to PU Connect">
                                            </div>
                                            <div class="mb-3">
                                                <label for="pageContent" class="form-label">Content</label>
                                                <textarea class="form-control" id="pageContent" rows="10"><h1>Welcome to PU Connect</h1><p>The premier mentorship platform connecting students across the university.</p></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pageMeta" class="form-label">Meta Description</label>
                                                <input type="text" class="form-control" id="pageMeta" value="PU Connect facilitates mentorship relationships between students">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update Content</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other tabs would go here -->
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple AJAX for mentor approval
        document.querySelectorAll('.approve-mentor').forEach(btn => {
            btn.addEventListener('click', function() {
                const appId = this.getAttribute('data-id');
                if(confirm('Approve this mentor application?')) {
                    fetch('approve_mentor.php?id=' + appId)
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                this.closest('tr').remove();
                                alert('Mentor approved successfully!');
                            }
                        });
                }
            });
        });

        // Similar for reject-mentor
    </script>
</body>
</html>