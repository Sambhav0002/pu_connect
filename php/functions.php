<?php
require_once 'conn.php';

// Get all mentors with optional filtering
function getMentors($filters = []) {
    global $conn;
    
    $sql = "SELECT `id`, `full_name`, `email`, `department`, `phone`, `expertise`, `experience`, `interests` 
            FROM `users` 
            WHERE `role` = 'mentor'";
    
    $conditions = [];
    $params = [];
    $types = '';
    
    // Add search filter
    if (!empty($filters['search'])) {
        $conditions[] = "(full_name LIKE ? OR department LIKE ? OR expertise LIKE ? OR interests LIKE ?)";
        $searchTerm = "%{$filters['search']}%";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $types .= 'ssss';
    }
    
    // Add expertise filter
    if (!empty($filters['expertise'])) {
        $conditions[] = "(expertise LIKE ? OR interests LIKE ?)";
        $expertiseTerm = "%{$filters['expertise']}%";
        $params = array_merge($params, [$expertiseTerm, $expertiseTerm]);
        $types .= 'ss';
    }
    
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $mentors = [];
    while ($row = $result->fetch_assoc()) {
        // Add profile image path
        $row['image'] = file_exists("images/{$row['id']}.jpg") 
                      ? "images/{$row['id']}.jpg" 
                      : "https://via.placeholder.com/300";
        $mentors[] = $row;
    }
    
    return $mentors;
}

// Get all unique expertise for filter tags
function getAllExpertise() {
    global $conn;
    
    $sql = "SELECT GROUP_CONCAT(DISTINCT expertise) as all_expertise 
            FROM users 
            WHERE role = 'mentor'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    $expertise = explode(',', $row['all_expertise']);
    $expertise = array_map('trim', $expertise);
    $expertise = array_unique($expertise);
    sort($expertise);
    
    return array_filter($expertise);
}

// Send mentor request
function sendMentorRequest($mentorId, $menteeId, $message) {
    global $conn;
    
    $sql = "INSERT INTO mentor_requests (mentor_id, mentee_id, message, status, created_at)
            VALUES (?, ?, ?, 'pending', NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $mentorId, $menteeId, $message);
    
    return $stmt->execute();
}
?>