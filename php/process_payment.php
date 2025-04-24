<?php
// Start the session
session_start();
include('dbconnect.php');

// Check if the user is logged in
$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['id'] ?? 0;

// If the role or user_id is not set, redirect to login
if (!$role || !$user_id) {
    echo "Unauthorized access!";
    header('Location: login.php');
    exit;
}

// Get the booking ID and payment method from the POST request
$bookingID = $_POST['bookingID'] ?? 0;
$payment_method = $_POST['payment_method'] ?? '';

// Validate input
if (!$bookingID || !$payment_method) {
    echo "Invalid payment details!";
    exit;
}

// Fetch the booking details
$query = "SELECT b.BookingID, b.total_amount
          FROM booking b
          WHERE b.BookingID = ? AND b.booked_by_id = ? AND b.booked_by_role = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $bookingID, $user_id, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
    $total_amount = $booking['total_amount'];
} else {
    echo "Booking not found!";
    exit;
}

// Process the payment here (for example, via a payment gateway)
// In this case, we assume payment is successfully processed

// Insert the payment into the payment table
$transactionID = uniqid('txn_'); // Generate a unique transaction ID (could be from a payment gateway)
$paymentStatus = 'Paid'; // Set payment status as Paid

$query = "INSERT INTO payment (BookingID, paid_amount, payment_method, transactionID, paymentStatus) 
          VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("idsss", $bookingID, $total_amount, $payment_method, $transactionID, $paymentStatus);

if ($stmt->execute()) {
    // Update the booking status to 'Paid' after successful payment
    $update_query = "UPDATE booking SET status = 'Confirmed' WHERE BookingID = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $bookingID);
    $update_stmt->execute();

    echo "Payment successfully processed! Booking status updated.";
} else {
    echo "Payment failed. Please try again!";
}
?>
