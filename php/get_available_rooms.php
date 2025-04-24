<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('db.php');

// Get check-in and check-out dates from the POST request
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$room_type = $_POST['room_type']; // Optional: To filter rooms by type

// Query to fetch available rooms based on check-in and check-out dates
$query = "
    SELECT room_id AS id, room_name AS name 
    FROM rooms 
    WHERE room_id NOT IN (
        -- Check if room is already booked during the selected dates
        SELECT room_id 
        FROM bookings 
        WHERE (checkin_date BETWEEN '$checkin' AND '$checkout') 
        OR (checkout_date BETWEEN '$checkin' AND '$checkout')
    )
    AND ('$checkin' > checkout_date OR '$checkout' < checkin_date)"; // Ensure availability after check-out

// Optional: Add filter by room type if provided
if (!empty($room_type)) {
    $query .= " AND room_type = '$room_type'";
}

// Execute the query
$result = $conn->query($query);

// Initialize an array to hold the rooms data
$rooms = array();
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

// Return the available rooms as a JSON response
echo json_encode($rooms);
?>
