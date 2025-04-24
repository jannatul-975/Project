<?php
include('db.php');

$university_id = $_POST['university_id'];

// Query to fetch departments based on university_id
$query = "SELECT department_id AS id, name FROM department WHERE university_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $university_id); // Bind the university_id as an integer
$stmt->execute();
$result = $stmt->get_result();

$departments = array();
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

echo json_encode($departments);
?>
