<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Meetings Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMeetingModal">
            <i class="fas fa-plus me-1"></i> Schedule New Meeting
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Meetings</h5>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary">Upcoming</button>
                <button class="btn btn-sm btn-outline-secondary">Past</button>
                <button class="btn btn-sm btn-outline-secondary">All</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mentee</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($meetings as $meeting): ?>
                        <tr>
                            <td><?= htmlspecialchars($meeting['mentee_name']) ?></td>
                            <td><?= date('M j, Y g:i A', strtotime($meeting['meeting_time'])) ?></td>
                            <td><?= htmlspecialchars($meeting['meeting_type']) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    strtotime($meeting['meeting_time']) < time() ? 'secondary' : 'success'
                                ?>">
                                    <?= strtotime($meeting['meeting_time']) < time() ? 'Completed' : 'Upcoming' ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">Details</button>
                                <button class="btn btn-sm btn-outline-danger">Cancel</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- New Meeting Modal -->
<div class="modal fade" id="newMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule New Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="meetingForm">
                    <div class="mb-3">
                        <label class="form-label">Select Mentee</label>
                        <select class="form-select" required>
                            <?php foreach ($mentees as $mentee): ?>
                            <option value="<?= $mentee['id'] ?>"><?= htmlspecialchars($mentee['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Type</label>
                        <select class="form-select" required>
                            <option value="virtual">Virtual Meeting</option>
                            <option value="in_person">In-Person</option>
                            <option value="phone_call">Phone Call</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date & Time</label>
                        <input type="datetime-local" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agenda</label>
                        <textarea class="form-control" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="meetingForm" class="btn btn-primary">Schedule Meeting</button>
            </div>
        </div>
    </div>
</div>