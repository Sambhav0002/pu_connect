<?php
// Authentication and DB connection code same as before
// Additional query to fetch mentee details
$mentee_details_stmt = $pdo->prepare("
    SELECT u.*, mm.joined_date 
    FROM users u
    JOIN mentor_mentee mm ON u.id = mm.mentee_id
    WHERE mm.mentor_id = ? AND u.id = ?
");
$mentee_details_stmt->execute([$mentor_id, $_GET['mentee_id']]);
$mentee_details = $mentee_details_stmt->fetch();
?>

<!-- HTML Structure similar to dashboard -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Mentee Profile</h2>
        <a href="mentor_dashboard.php" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="row">
        <!-- Mentee Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150" alt="Mentee" class="rounded-circle mb-3" width="120">
                    <h4><?= htmlspecialchars($mentee_details['full_name']) ?></h4>
                    <p class="text-muted mb-1"><?= htmlspecialchars($mentee_details['program']) ?></p>
                    <p class="text-muted">Year <?= htmlspecialchars($mentee_details['year_level']) ?></p>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary me-2">Since <?= date('M Y', strtotime($mentee_details['joined_date'])) ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-envelope"></i> Message
                        </a>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fas fa-calendar"></i> Schedule Meeting
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mentee Details -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#profile" data-bs-toggle="tab">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#progress" data-bs-toggle="tab">Progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#meetings" data-bs-toggle="tab">Meetings</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <!-- Profile Tab -->
                    <div class="tab-pane active" id="profile">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Email:</strong> <?= htmlspecialchars($mentee_details['email']) ?></p>
                                <p><strong>Department:</strong> <?= htmlspecialchars($mentee_details['department']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Contact:</strong> <?= htmlspecialchars($mentee_details['phone'] ?? 'Not provided') ?></p>
                                <p><strong>Joined:</strong> <?= date('M j, Y', strtotime($mentee_details['joined_date'])) ?></p>
                            </div>
                        </div>
                        <hr>
                        <h5 class="mb-3">Academic Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>CGPA:</strong> <?= $mentee_details['cgpa'] ?? 'Not provided' ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Interests:</strong> <?= $mentee_details['interests'] ?? 'Not specified' ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Tab -->
                    <div class="tab-pane" id="progress">
                        <h5>Mentorship Goals Progress</h5>
                        <!-- Progress bars similar to dashboard -->
                    </div>
                    
                    <!-- Meetings Tab -->
                    <div class="tab-pane" id="meetings">
                        <h5>Past Meetings</h5>
                        <!-- Meeting history table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>