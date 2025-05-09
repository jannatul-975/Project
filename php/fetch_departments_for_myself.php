<?php
// Include the database connection file
include('db.php');

// Query to fetch departments where university_id = 1 (Khulna University)
$query = "SELECT id, name FROM department WHERE university_id = 1";

// Execute the query
$result = $conn->query($query);

// Initialize an array to hold the department data
$departments = array();

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch each department and add to the array
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Return the departments as a JSON response
echo json_encode($departments);
?>
