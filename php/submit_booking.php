<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('db.php');

// Check if the user is logged in
$role = $_SESSION['role'] ?? ''; // Get role from session
$user_id = $_SESSION['id'] ?? 0; // Get user ID from session

// If the role or user_id is not set, redirect to login
if (!$role || !$user_id) {
    echo "Unauthorized access!";
    header('Location: login.php'); // Redirect to login page
    exit;
}

// Handle form submission when the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data from the booking form
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $rooms = $_POST['room']; // Array of selected room IDs (single or multiple)
    $days = $_POST['days'];
    $guestNumber = $_POST['guestNumber'] ?? 1; // Get guest number from the form, default to 1 if not set
    $booking_type = $_POST['booking_type']; // "myself" or "guest"
    $guest_type = $_POST['guest_type'] ?? ''; // "academic" or "non_academic" if guest booking
    $discipline = $_POST['discipline'] ?? ''; // Discipline name if "myself"
    $department = $_POST['department'] ?? ''; // Department if "guest" or "myself"
    $guest_phone = $_POST['guest_phone'] ?? ''; // Phone for non-academic guests
    $guest_address = $_POST['guest_address'] ?? ''; // Address for non-academic guests
    $other_info = $_POST['other_info'] ?? ''; // Other information for non-academic guests

    // Ensure guestNumber is cast to an integer
    $guestNumber = (int) $guestNumber;  // Casting to integer

    // Initialize total price
    $total_price = 0;

    // Prepare an array to store booking details for each room
    $booking_details = [];

    // Insert guest information into the viewguest table
    $guest_info = '';
    if ($booking_type == 'myself') {
        // If it's "myself", set the university and department information
        $guest_info = "Khulna University (Khulna, Bangladesh)";
    } elseif ($booking_type == 'guest') {
        if ($guest_type == 'academic') {
            // If it's an academic guest, set the university and department
            $guest_info = $_POST['guest_university'] . ', ' . $department;
        } elseif ($guest_type == 'non_academic') {
            // If it's a non-academic guest, set phone number, address, and other info
            $guest_info = "Phone: $guest_phone, Address: $guest_address, $other_info";
        }
    }

    // Insert guest data into viewguest table
    $stmt = $conn->prepare("INSERT INTO viewguest (name, designation, dept_name, phone_no, checkin, checkout, roomno, guestInformation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $designation, $department, $guest_phone, $checkin, $checkout, $rooms[0], $guest_info);

    // Set values for name, designation, and department
    $name = $_SESSION['name']; // Get logged-in user's name
    $designation = $_SESSION['role']; // Get logged-in user's role/designation

    $stmt->execute();  // Insert the guest data into the `viewguest` table
    $guest_id = $stmt->insert_id;  // Get the inserted guest's ID
    $stmt->close();

    // Insert booking details into the booking table for each room
    foreach ($rooms as $room_id) {
        // Fetch room details (including price) from the room table
        $stmt = $conn->prepare("SELECT RoomNo, pricePerNight FROM room WHERE RoomID = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $stmt->bind_result($room_no, $price_per_night);
        $stmt->fetch();
        $stmt->close();

        // Check if room details are found
        if (!$room_no || !$price_per_night) {
            echo "Room not found!";
            exit;
        }

        // Calculate the price for this room
        $room_price = $price_per_night * $days;
        $total_price += $room_price; // Add to total price

        // Prepare booking data for each room
        $booking_details[] = [
            'room_id' => $room_id,
            'room_no' => $room_no,
            'price' => $room_price,
        ];
    }

    // Insert booking data into the booking table
    foreach ($booking_details as $details) {
        $status = "Confirmed"; // Set status as confirmed

        // Insert the data into the booking table
        $stmt = $conn->prepare("INSERT INTO booking (booked_by_role, booked_by_id, RoomNo, checkInDate, checkOutDate, total_amount, status, guest_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisssdsi", $role, $user_id, $details['room_no'], $checkin, $checkout, $total_price, $status, $guestNumber);
        $stmt->execute();
        $booking_id = $stmt->insert_id;  // Get the ID of the inserted booking record
        $stmt->close();

        // Update the room status in the room table (mark it as booked)
        $stmt = $conn->prepare("UPDATE room SET status = 'Booked' WHERE RoomID = ?");
        $stmt->bind_param("i", $details['room_id']);
        $stmt->execute();
        $stmt->close();
    }

    // Return success message
    echo "Booking confirmed! Your total price is BDT $total_price for $days night(s).";
}
?>
