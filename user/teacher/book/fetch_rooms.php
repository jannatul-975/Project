<?php
// fetch_rooms.php
include('../../../db.php'); // Include database connection

// Get the room type from the request
$room_type = $_GET['room_type'];

if ($room_type) {
    $query = "SELECT * FROM room WHERE room_type = '$room_type' AND status = 'available'";
} else {
    // If no room type is selected, fetch all available rooms
    $query = "SELECT * FROM room WHERE status = 'available'";
}

$result = $conn->query($query);

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Store the room name along with room id and type
        $rooms[] = [
            'RoomID' => $row['RoomID'],
            'RoomName' => $row['RoomName'],  // Include the room name
            'room_type' => $row['room_type'],
            'pricePerNight' => $row['pricePerNight']
        ];
    }
}

echo json_encode($rooms); // Return the rooms as a JSON response
?>

