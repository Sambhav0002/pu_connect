<?php
require_once 'functions.php';

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PU Connect - Mentee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-handshake me-2"></i>PU Connect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-home me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mentee.php"><i class="fas fa-user-friends me-1"></i> My Mentors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-envelope me-1"></i> Messages</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> My Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-1"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-1"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                <form action="process_request.php" method="post">
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
</body>
</html>