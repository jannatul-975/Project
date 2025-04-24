<?php
include('db.php');

// Query to fetch all universities
$query = "SELECT university_id AS id, name FROM university";
$result = $conn->query($query);

$universities = array();
while ($row = $result->fetch_assoc()) {
    $universities[] = $row;
}

echo json_encode($universities);
?>
