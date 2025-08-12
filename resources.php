<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Resources Library</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newResourceModal">
            <i class="fas fa-plus me-1"></i> Add New Resource
        </button>
    </div>

    <div class="row">
        <!-- Resource Categories -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active">All Resources</a>
                        <a href="#" class="list-group-item list-group-item-action">Career Guidance</a>
                        <a href="#" class="list-group-item list-group-item-action">Academic Support</a>
                        <a href="#" class="list-group-item list-group-item-action">Research Materials</a>
                        <a href="#" class="list-group-item list-group-item-action">Workshop Recordings</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resources List -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Resources</h5>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search resources...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($resources as $resource): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($resource['title']) ?></h5>
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($resource['type']) ?></span>
                                    </div>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($resource['description']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Uploaded: <?= date('M j, Y', strtotime($resource['created_at'])) ?></small>
                                        <div>
                                            <a href="#" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Resource Modal -->
<div class="modal fade" id="newResourceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="resourceForm">
                    <div class="mb-3">
                        <label class="form-label">Resource Title</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resource Type</label>
                        <select class="form-select" required>
                            <option value="pdf">PDF Document</option>
                            <option value="video">Video</option>
                            <option value="link">Web Link</option>
                            <option value="presentation">Presentation</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File</label>
                        <input type="file" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Share With</label>
                        <select class="form-select" multiple>
                            <?php foreach ($mentees as $mentee): ?>
                            <option value="<?= $mentee['id'] ?>" selected><?= htmlspecialchars($mentee['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="resourceForm" class="btn btn-primary">Upload Resource</button>
            </div>
        </div>
    </div>
</div>