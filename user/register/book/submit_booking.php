<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
include('../../../db.php');

// Collect and sanitize form data
$booking_type = $_POST['booking_type'] ?? '';
$guest_phone = $_POST['guest_phone'] ?? '';
$checkin_date = $_POST['checkin'] ?? '';
$checkout_date = $_POST['checkout'] ?? '';
$room_type = $_POST['room_type'] ?? '';
$rooms = $_POST['room'] ?? [];
$travelPurpose = $_POST['travelPurpose'] ?? '';

// Validate input
if (empty($booking_type) || empty($checkin_date) || empty($checkout_date) || !is_array($rooms) || empty($travelPurpose)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing or invalid required fields.']);
    exit;
}

// Guest handling
$guest_id = null;
if (in_array($booking_type, ['academic', 'non_academic'])) {
    $stmt = $conn->prepare("SELECT guest_id FROM guest WHERE phone = ?");
    $stmt->bind_param("s", $guest_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $guest_id = $result->fetch_assoc()['guest_id'];
    } else {
        if ($booking_type === 'academic') {
            $university = $_POST['guest_university'] ?? '';
            $dept = $_POST['department_academic'] ?? '';
            if (empty($university) || empty($dept)) {
                echo json_encode(['status' => 'error', 'message' => 'Missing university/department for academic guest.']);
                exit;
            }
            $info = "$dept, $university";
            $stmt = $conn->prepare("INSERT INTO guest (phone, guestType, additionalInformation) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $guest_phone, $booking_type, $info);
        } else {
            $address = $_POST['guest_address'] ?? '';
            $info = $_POST['other_info'] ?? '';
            if (empty($address)) {
                echo json_encode(['status' => 'error', 'message' => 'Address missing for non-academic guest.']);
                exit;
            }
            $stmt = $conn->prepare("INSERT INTO guest (phone, address, guestType, additionalInformation) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $guest_phone, $address, $booking_type, $info);
        }

        if ($stmt->execute()) {
            $guest_id = $stmt->insert_id;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error inserting guest: ' . $stmt->error]);
            exit;
        }
    }
}

// Insert booking
$stmt = $conn->prepare("INSERT INTO booking (guestId, checkInDate, checkOutDate,travelPurpose, paymentStatus) VALUES (?, ?, ?, ?, 'Pending')");
$stmt->bind_param("issds", $guest_id, $checkin_date, $checkout_date,$travelPurpose);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Error inserting booking: ' . $stmt->error]);
    exit;
}

$booking_id = $stmt->insert_id;

// Insert into booking_room
foreach ($rooms as $room_id) {
    $stmt = $conn->prepare("INSERT INTO booking_room (RoomID, bookingId) VALUES (?, ?)");
    $stmt->bind_param("ii", $room_id, $booking_id);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Error linking room to booking: ' . $stmt->error]);
        exit;
    }
    
    $stmt = $conn->prepare("UPDATE room SET status = 'Booked' WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Error updating room status: ' . $stmt->error]);
        exit;
    }
}

// Return success
echo json_encode([
    'status' => 'success',
    'message' => 'Bookings successfully submitted.',
    'booking_id' => $booking_id
]);
?>
