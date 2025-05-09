<?php
include('../../../db.php');

// Get the filter and search values from GET request
$statusFilter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Base query to fetch applications
$query = "
    SELECT 
        ta.applicationId, 
        ta.userId,
        tu.name AS Name,  
        tu.dept_name AS discipline,
        tu.designation AS designation,
        ta.status as status, 
        ta.submission_date as submission_date
    FROM application ta
    JOIN user tu ON ta.userId = tu.id
    WHERE 1
";

// Apply filters
$params = [];
$types = '';

if ($statusFilter) {
    $query .= " AND ta.status = ?";
    $params[] = $statusFilter;
    $types .= 's';
}

if ($search) {
    $query .= " AND (tu.name LIKE ? OR tu.dept_name LIKE ? OR ta.submission_date LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'sss';
}

// Final ORDER BY clause
$query .= " ORDER BY ta.submission_date DESC";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind parameters if needed
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Display results
if ($result->num_rows > 0) {
    echo '<table class="table">';
    echo '<thead><tr><th>Name</th><th>Discipline</th><th>Designation</th><th>Status</th><th>Submission Date</th><th>Action</th></tr></thead><tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['discipline']) . '</td>';
        echo '<td>' . htmlspecialchars($row['designation']) . '</td>';
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '<td>' . htmlspecialchars($row['submission_date']) . '</td>';
        echo '<td><a href="application.php?id=' . $row['applicationId'] . '">View Details</a></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo "<div class='alert alert-info'>No applications found.</div>";
}
?>
