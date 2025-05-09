<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db.php');

// Check if the user is logged in
$role = $_SESSION['role'] ?? ''; // Get user role
$user_id = $_SESSION['user_id'] ?? 0; // Get user ID

// If the role or user_id is not set, redirect to login
if (!$role || !$user_id) {
    echo "Unauthorized access!";
    header('Location: login.php');
    exit;
}

// Initialize filter and search variables
$statusFilter = $_GET['status'] ?? ''; // Get filter for status (Paid, Pending)
$checkoutSearch = $_GET['checkout'] ?? ''; // Get search value for checkout date
$checkinSearch = $_GET['checkin'] ?? ''; // Get search value for check-in date
$purposeSearch = $_GET['guestType'] ?? ''; // Get search value for purpose (AcademicGuest,NonAcademicGuest)
$guestPhoneSearch = $_GET['guest_phone'] ?? ''; // Get search value for guest's phone number

// Query to fetch the user's booking history with filters
$query = "
    SELECT b.bookingId, b.checkInDate, b.checkOutDate, b.totalAmount, g.phone, g.guestType, p.paymentStatus
    FROM booking b
    JOIN guest g ON b.guestId = g.guestId 
    JOIN payment p ON b.bookingId = p.bookingId
    WHERE b.userId = ?";

// Add filters to the query dynamically based on the user's input
if ($statusFilter) {
    $query .= " AND p.status = ?";
}

if ($checkoutSearch) {
    $query .= " AND b.checkOutDate = ?";
}

if ($checkinSearch) {
    $query .= " AND b.checkInDate = ?";
}

if ($purposeSearch) {
    $query .= " AND g.guestType LIKE ?";
}

if ($guestPhoneSearch) {
    $query .= " AND g.phone LIKE ?";
}

// Prepare the statement
$stmt = $conn->prepare($query);

// Initialize the parameter types and the parameters array
$types = 'i'; // Initial type is 'i' for integer (user_id)
$params = [$user_id]; // Initialize parameters array with user_id

// Bind parameters based on the filters
if ($statusFilter) {
    $types .= 's'; // 's' for string (status)
    $params[] = $statusFilter;
}

if ($checkoutSearch) {
    $types .= 's'; // 's' for string (checkout date)
    $params[] = $checkoutSearch;
}

if ($checkinSearch) {
    $types .= 's'; // 's' for string (checkin date)
    $params[] = $checkinSearch;
}

if ($purposeSearch) {
    $types .= 's'; // 's' for string (purpose)
    $params[] = '%' . $purposeSearch . '%';
}

if ($guestPhoneSearch) {
    $types .= 's'; // 's' for string (guest phone)
    $params[] = '%' . $guestPhoneSearch . '%';
}

// Bind the parameters dynamically
$stmt->bind_param($types, ...$params);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
    // Output the bookings
    while ($row = $result->fetch_assoc()) {
        // Add additional fields to display as needed
        echo "Check-In Date: " . $row['checkInDate'] . "<br>";
        echo "Check-Out Date: " . $row['checkOutDate'] . "<br>";
        echo "Total Amount: " . $row['totalAmount'] . "<br>";
        echo "Purpose: " . $row['guestType'] . "<br>";
        echo "Guest phone: " . $row['phone'] . "<br>";
        // echo "Guest Info: " . $row['guestInformation'] . "<br><br>";
    }
} else {
    echo "No bookings found.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
