<?php
include('../../../db.php');

$query = "SELECT * FROM university";
$result = $conn->query($query);

$universities = array();
while ($row = $result->fetch_assoc()) {
    $universities[] = $row;
}

echo json_encode($universities);
?>
