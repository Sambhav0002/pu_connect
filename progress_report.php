<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Progress Reports</h2>
        <div class="btn-group">
            <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All Mentees</a></li>
                <li><hr class="dropdown-divider"></li>
                <?php foreach ($mentees as $mentee): ?>
                <li><a class="dropdown-item" href="#"><?= htmlspecialchars($mentee['full_name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#overview" data-bs-toggle="tab">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#goals" data-bs-toggle="tab">Goals Tracking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#milestones" data-bs-toggle="tab">Milestones</a>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content">
            <!-- Overview Tab -->
            <div class="tab-pane active" id="overview">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-primary"><?= count($mentees) ?></h3>
                                <p class="text-muted mb-0">Active Mentees</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-success"><?= $avg_progress ?>%</h3>
                                <p class="text-muted mb-0">Average Progress</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-info"><?= $completed_milestones ?></h3>
                                <p class="text-muted mb-0">Completed Milestones</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h5 class="mb-3">Progress Overview</h5>
                <canvas id="progressChart" height="150"></canvas>
            </div>
            
            <!-- Goals Tracking Tab -->
            <div class="tab-pane" id="goals">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mentee</th>
                                <th>Career Guidance</th>
                                <th>Academic Support</th>
                                <th>Skill Development</th>
                                <th>Overall</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mentees as $mentee): ?>
                            <tr>
                                <td><?= htmlspecialchars($mentee['full_name']) ?></td>
                                <td>
                                    <div class="progress progress-bar-custom">
                                        <div class="progress-bar" style="width: <?= rand(30,90) ?>%"></div>
                                    </div>
                                    <small><?= rand(30,90) ?>%</small>
                                </td>
                                <td>
                                    <div class="progress progress-bar-custom">
                                        <div class="progress-bar bg-success" style="width: <?= rand(30,90) ?>%"></div>
                                    </div>
                                    <small><?= rand(30,90) ?>%</small>
                                </td>
                                <td>
                                    <div class="progress progress-bar-custom">
                                        <div class="progress-bar bg-info" style="width: <?= rand(30,90) ?>%"></div>
                                    </div>
                                    <small><?= rand(30,90) ?>%</small>
                                </td>
                                <td>
                                    <strong><?= rand(40,85) ?>%</strong>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Milestones Tab -->
            <div class="tab-pane" id="milestones">
                <div class="row">
                    <?php foreach ($mentees as $mentee): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?= htmlspecialchars($mentee['full_name']) ?></h5>
                                <span class="badge bg-primary"><?= rand(2,5) ?>/5 Milestones</span>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Milestone <?= $i ?>
                                        <span class="badge bg-<?= $i <= 3 ? 'success' : 'secondary' ?>">
                                            <?= $i <= 3 ? 'Completed' : 'Pending' ?>
                                        </span>
                                    </li>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Progress Chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    const progressChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($mentees, 'full_name')) ?>,
            datasets: [
                {
                    label: 'Career Guidance',
                    data: [65, 59, 80, 81],
                    backgroundColor: '#4361ee'
                },
                {
                    label: 'Academic Support',
                    data: [72, 48, 60, 79],
                    backgroundColor: '#4895ef'
                },
                {
                    label: 'Skill Development',
                    data: [55, 63, 72, 85],
                    backgroundColor: '#4cc9f0'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    max: 100
                }
            }
        }
    });
</script>