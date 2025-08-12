<div class="main-content">
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150" alt="Profile" class="rounded-circle mb-3" width="150">
                    <h4><?= htmlspecialchars($mentor['full_name']) ?></h4>
                    <p class="text-muted mb-1"><?= htmlspecialchars($mentor['position']) ?></p>
                    <p class="text-muted"><?= htmlspecialchars($mentor['department']) ?></p>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary me-2">Mentor</span>
                        <span class="badge bg-success"><?= count($mentees) ?> Mentees</span>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Contact Information</h5>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> <?= htmlspecialchars($mentor['email']) ?></p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> <?= htmlspecialchars($mentor['phone'] ?? 'Not provided') ?></p>
                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> <?= htmlspecialchars($mentor['office_location'] ?? 'Not specified') ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#personal" data-bs-toggle="tab">Personal Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#expertise" data-bs-toggle="tab">Expertise</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#settings" data-bs-toggle="tab">Settings</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane active" id="personal">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars(explode(' ', $mentor['full_name'])[0]) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars(explode(' ', $mentor['full_name'])[1] ?? '') ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Position</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($mentor['position']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($mentor['department']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control" rows="4"><?= htmlspecialchars($mentor['bio'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                    
                    <!-- Expertise Tab -->
                    <div class="tab-pane" id="expertise">
                        <h5 class="mb-3">Areas of Expertise</h5>
                        <div class="mb-3">
                            <label class="form-label">Primary Expertise</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($mentor['expertise']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Skills</label>
                            <select class="form-select" multiple>
                                <option selected>Career Counseling</option>
                                <option selected>Academic Writing</option>
                                <option>Research Methodology</option>
                                <option>Public Speaking</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Availability</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="monday" checked>
                                <label class="form-check-label" for="monday">Monday</label>
                            </div>
                            <!-- Repeat for other days -->
                        </div>
                        <button type="submit" class="btn btn-primary">Update Expertise</button>
                    </div>
                    
                    <!-- Settings Tab -->
                    <div class="tab-pane" id="settings">
                        <h5 class="mb-3">Account Settings</h5>
                        <div class="mb-3">
                            <label class="form-label">Change Password</label>
                            <input type="password" class="form-control" placeholder="Current Password">
                            <input type="password" class="form-control mt-2" placeholder="New Password">
                            <input type="password" class="form-control mt-2" placeholder="Confirm New Password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notification Preferences</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="smsNotifications">
                                <label class="form-check-label" for="smsNotifications">SMS Notifications</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>