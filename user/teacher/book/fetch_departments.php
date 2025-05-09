<?php
include('../../../db.php');

$university_id = $_POST['university_id'];
$query = "SELECT * FROM department WHERE university_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $university_id);
$stmt->execute();

$result = $stmt->get_result();
$departments = array();
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

echo json_encode($departments);
?>
