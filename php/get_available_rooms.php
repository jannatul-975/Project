<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

include('db.php');

$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$room_type = $_POST['room_type'] ?? '';

if (!$checkin || !$checkout) {
    echo json_encode(["error" => "Check-in and check-out dates are required."]);
    exit;
}

$query = "
    SELECT RoomID AS id, RoomName AS name, room_type
    FROM room
    WHERE status = 'Available'
    AND RoomID NOT IN (
        SELECT RoomID FROM booking
        WHERE (checkin_date < ? AND checkout_date > ?)
    )
";

$params = [$checkout, $checkin];
$types = "ss";

if (!empty($room_type)) {
    $query .= " AND room_type = ?";
    $params[] = $room_type;
    $types .= "s";
}

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "Database error: " . $conn->error]);
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

if (empty($rooms)) {
    echo json_encode(["message" => "No rooms available for the selected dates."]);
    exit;
}

echo json_encode($rooms);
