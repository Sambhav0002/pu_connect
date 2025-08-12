
<?php
session_start();
require 'php/conn.php'; 

$query = "SELECT COUNT(*) AS mentor_count FROM `users` WHERE role = 'mentor'";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $mentorCount = $row['mentor_count'];
   
} else {
    echo "Error: " . $conn->error;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PU Connect - Mentee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="php/styles.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
            color: var(--text-color);
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            letter-spacing: 0.05rem;
            z-index: 1;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            padding: 0.75rem 1rem;
            margin-bottom: 0.2rem;
        }
        
        .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link i {
            margin-right: 0.25rem;
        }
        
        .active {
            color: #fff !important;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
            font-weight: 700;
        }
        
        .profile-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        
        .mentor-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        
        .mentor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .interest-badge {
            margin-right: 0.3rem;
            margin-bottom: 0.3rem;
        }
        
        .progress {
            height: 0.6rem;
        }
        
        .bg-mentoring {
            background-color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                height: auto;
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar d-md-block bg-primary text-white">
                <div class="sidebar-brand d-flex align-items-center justify-content-center">
                    <i class="fas fa-hands-helping me-2"></i>
                    <span>PU Connect</span>
                </div>
                <hr class="sidebar-divider my-0">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-fw fa-user"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-fw fa-calendar"></i>
                            <span>Sessions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-fw fa-comments"></i>
                            <span>Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-fw fa-book"></i>
                            <span>Resources</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-fw fa-exit"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ml-sm-auto px-4 py-4">
                <!-- Top Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Mentee Dashboard</h1>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i> <?php echo $_SESSION['user_name'] ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class=" fas fa-sign-out me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Active Mentors</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $mentorCount;?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Upcoming Sessions</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Mentoring Progress</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">65%</div>
                                            </div>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Requests</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">1</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Row -->
                <?php
require_once 'php/functions.php';

// Get filters from URL
$filters = [
    'search' => $_GET['search'] ?? '',
    'expertise' => $_GET['expertise'] ?? ''
];

// Get mentors based on filters
$mentors = getMentors($filters);

// Get all unique expertise for filter tags
$allExpertise = getAllExpertise();
?>
    
    <!-- Main Content -->
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-search me-2"></i>Find Your Mentor</h2>
                        <p class="card-text text-muted">Browse through our network of experienced mentors and connect with those who match your interests.</p>
                        
                        <!-- Search and Filter Form -->
                        <form method="get" action="" class="mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" name="search" placeholder="Search by name, department or expertise" value="<?= htmlspecialchars($filters['search']) ?>">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="filter-tags">
                                        <span class="me-2">Filter by:</span>
                                        <?php foreach ($allExpertise as $expertise): ?>
                                            <a href="?expertise=<?= urlencode($expertise) ?>" class="badge rounded-pill bg-primary text-white text-decoration-none me-1 mb-1 <?= ($filters['expertise'] === $expertise) ? 'active-filter' : '' ?>">
                                                <?= htmlspecialchars($expertise) ?>
                                            </a>
                                        <?php endforeach; ?>
                                        <?php if ($filters['expertise']): ?>
                                            <a href="?" class="badge rounded-pill bg-danger text-white text-decoration-none me-1 mb-1">
                                                Clear Filter
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mentors Grid -->
        <div class="row">
            <?php if (empty($mentors)): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>No mentors found matching your criteria. Try adjusting your filters.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($mentors as $mentor): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 mentor-card">
                            <!--<img src="<?= $mentor['image'] ?>" class="card-img-top mentor-img" alt="<?= htmlspecialchars($mentor['full_name']) ?>">-->
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($mentor['full_name']) ?></h5>
                                <p class="card-subtitle text-muted mb-2">
                                    <i class="fas fa-building me-1"></i><?= htmlspecialchars($mentor['department']) ?>
                                </p>
                                <p class="card-text text-muted small">
                                    <i class="fas fa-briefcase me-1"></i><?= htmlspecialchars($mentor['experience']) ?> experience
                                </p>
                                <p class="card-text text-muted small">
                                    <i class="fas fa-briefcase me-1"></i><?= htmlspecialchars($mentor['interests']) ?> 
                                </p>
                                <div class="expertise-tags mb-3">
                                    <?php foreach (explode(',', $mentor['expertise']) as $tag): ?>
                                        <span class="badge bg-light text-dark me-1 mb-1"><?= htmlspecialchars(trim($tag)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                
                                <button class="btn btn-primary w-100 request-mentor" 
                                        data-mentor-id="<?= $mentor['id'] ?>" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#requestModal">
                                    <i class="fas fa-paper-plane me-2"></i>Request Mentorship
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Request Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="requestModalLabel">Send Mentorship Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="php/process_request.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="mentor_id" id="mentorId">
                        <div class="mb-3">
                            <label for="requestMessage" class="form-label">Your Message</label>
                            <textarea class="form-control" id="requestMessage" name="message" rows="4" required placeholder="Tell the mentor why you'd like to connect..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set mentor ID when request button is clicked
        document.querySelectorAll('.request-mentor').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('mentorId').value = this.dataset.mentorId;
            });
        });
    </script>


<script>
// Function to fetch recommended mentors via AJAX
function fetchRecommendedMentors() {
    const container = document.getElementById('mentorsContainer');
    container.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Loading recommended mentors...</p>
        </div>
    `;
    
    fetch('fetch_mentors.php?user_id=<?php echo $userId; ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.mentors.length > 0) {
                renderMentors(data.mentors);
            } else {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <p>No mentors found matching your interests.</p>
                        <button class="btn btn-sm btn-primary" onclick="fetchRecommendedMentors()">
                            <i class="fas fa-sync-alt me-1"></i> Try Again
                        </button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p>Failed to load mentors. Please try again.</p>
                    <button class="btn btn-sm btn-primary" onclick="fetchRecommendedMentors()">
                        <i class="fas fa-sync-alt me-1"></i> Retry
                    </button>
                </div>
            `;
        });
}

// Function to render mentors from AJAX response
function renderMentors(mentors) {
    let html = '<div class="row">';
    
    mentors.forEach(mentor => {
        const interests = mentor.interests.slice(0, 3);
        
        html += `
            <div class="col-md-4 mb-4">
                <div class="card mentor-card h-100">
                    <div class="card-body text-center">
                        <img src="${mentor.profileImage || 'https://randomuser.me/api/portraits/med/lego/1.jpg'}" 
                             alt="${mentor.name}" class="profile-img rounded-circle mb-3">
                        <h5 class="card-title">${mentor.title ? mentor.title + ' ' : ''}${mentor.name}</h5>
                        <p class="text-muted">${mentor.department || mentor.expertise || 'Mentor'}</p>
                        <div class="mb-3">
                            ${interests.map(interest => 
                                `<span class="badge bg-primary interest-badge me-1">${interest}</span>`
                            ).join('')}
                        </div>
                        <p class="card-text small">${mentor.bio || 'Experienced mentor in their field.'}</p>
                        <button class="btn btn-sm btn-primary request-mentor-btn" data-mentor-id="${mentor.id}">
                            <i class="fas fa-user-plus me-1"></i> Request Mentor
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    document.getElementById('mentorsContainer').innerHTML = html;
    
    // Add event listeners to new buttons
    document.querySelectorAll('.request-mentor-btn').forEach(button => {
        button.addEventListener('click', function() {
            requestMentor(this.getAttribute('data-mentor-id'));
        });
    });
}

// Function to handle mentor request
function requestMentor(mentorId) {
    fetch('request_mentor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mentor_id=${mentorId}&user_id=<?php echo $userId; ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Mentor request sent successfully!');
        } else {
            alert('Failed to send request: ' + (data.message || 'Please try again.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send request. Please try again.');
    });
}

// Add event listeners to initial buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.request-mentor-btn').forEach(button => {
        button.addEventListener('click', function() {
            requestMentor(this.getAttribute('data-mentor-id'));
        });
    });
});
</script>

    <!-- Request Mentor Modal -->
    <div class="modal fade" id="requestMentorModal" tabindex="-1" aria-labelledby="requestMentorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="requestMentorModalLabel">Request a New Mentor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="mentorRequestForm">
                        <div class="mb-3">
                            <label for="interestArea" class="form-label">Primary Interest Area</label>
                            <select class="form-select" id="interestArea" required>
                                <option value="" selected disabled>Select your primary interest</option>
                                <option value="computer_science">Computer Science</option>
                                <option value="business">Business Administration</option>
                                <option value="engineering">Engineering</option>
                                <option value="design">Design</option>
                                <option value="data_science">Data Science</option>
                                <option value="cybersecurity">Cybersecurity</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Interests (Select up to 3)</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestProgramming" value="Programming">
                                        <label class="form-check-label" for="interestProgramming">Programming</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestAI" value="Artificial Intelligence">
                                        <label class="form-check-label" for="interestAI">Artificial Intelligence</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestWebDev" value="Web Development">
                                        <label class="form-check-label" for="interestWebDev">Web Development</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestEntrepreneurship" value="Entrepreneurship">
                                        <label class="form-check-label" for="interestEntrepreneurship">Entrepreneurship</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestMarketing" value="Marketing">
                                        <label class="form-check-label" for="interestMarketing">Marketing</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestFinance" value="Finance">
                                        <label class="form-check-label" for="interestFinance">Finance</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestUX" value="UX Design">
                                        <label class="form-check-label" for="interestUX">UX Design</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestDataScience" value="Data Science">
                                        <label class="form-check-label" for="interestDataScience">Data Science</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="interestCybersecurity" value="Cybersecurity">
                                        <label class="form-check-label" for="interestCybersecurity">Cybersecurity</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mentorType" class="form-label">Preferred Mentor Type</label>
                            <select class="form-select" id="mentorType">
                                <option value="any" selected>Any</option>
                                <option value="faculty">Faculty Member</option>
                                <option value="industry">Industry Professional</option>
                                <option value="alumni">Alumni</option>
                                <option value="senior">Senior Student</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requestMessage" class="form-label">Additional Message (Optional)</label>
                            <textarea class="form-control" id="requestMessage" rows="3" placeholder="Tell potential mentors why you'd like to connect..."></textarea>
                        </div>
                    </form>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i> Your request will be sent to matching mentors. You'll be notified when a mentor accepts your request.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitRequestBtn">Submit Request</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Your mentor request has been submitted successfully!
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle mentor request button clicks
        document.querySelectorAll('.request-mentor-btn').forEach(button => {
            button.addEventListener('click', function() {
                const mentorId = this.getAttribute('data-mentor-id');
                // In a real app, this would send an AJAX request to the server
                alert(`Mentor request sent for mentor ID: ${mentorId}`);
                
                // Show success toast
                const toast = new bootstrap.Toast(document.getElementById('successToast'));
                toast.show();
            });
        });

        // Handle form submission for the request mentor modal
        document.getElementById('submitRequestBtn').addEventListener('click', function() {
            const form = document.getElementById('mentorRequestForm');
            if (form.checkValidity()) {
                // In a real app, this would submit the form data to the server
                alert('Mentor request submitted based on your interests!');
                
                // Hide the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('requestMentorModal'));
                modal.hide();
                
                // Show success toast
                const toast = new bootstrap.Toast(document.getElementById('successToast'));
                toast.show();
            } else {
                form.reportValidity();
            }
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>