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

// Get the booking ID from the URL
$bookingID = $_GET['bookingID'] ?? 0;

if ($bookingID) {
    // Fetch the booking details
    $query = "SELECT b.BookingID, b.RoomNo, b.checkInDate, b.checkOutDate, b.total_amount, b.status, r.room_type
              FROM booking b
              JOIN room r ON b.RoomNo = r.RoomID
              WHERE b.BookingID = ? AND b.booked_by_id = ? AND b.booked_by_role = 'teacher'";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $bookingID, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        echo "Invalid booking ID or unauthorized access.";
        exit;
    }
} else {
    echo "Booking ID is required.";
    exit;
}

// Payment processing logic (mocked for this example)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'] ?? '';
    $transactionID = uniqid('txn_'); // Generate a unique transaction ID

    if ($payment_method) {
        // Update the payment status to 'Paid'
        $paymentStatus = 'Paid';
        $paymentAmount = $booking['total_amount'];

        // Insert the payment record
        $insert_payment_query = "INSERT INTO payment (BookingID, paid_amount, payment_method, transactionID, paymentStatus)
                                 VALUES (?, ?, ?, ?, ?)";
        $payment_stmt = $conn->prepare($insert_payment_query);
        $payment_stmt->bind_param("idsss", $bookingID, $paymentAmount, $payment_method, $transactionID, $paymentStatus);

        if ($payment_stmt->execute()) {
            // Update the booking status to 'Confirmed' after successful payment
            $update_booking_query = "UPDATE booking SET status = 'Confirmed' WHERE BookingID = ?";
            $update_stmt = $conn->prepare($update_booking_query);
            $update_stmt->bind_param("i", $bookingID);
            $update_stmt->execute();

            // Redirect back to the booking history page with success message
            header('Location: booking_history.php?status=success');
            exit();
        } else {
            echo "Payment processing failed. Please try again.";
        }
    } else {
        echo "Please select a payment method.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Process Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php include('header_sidebar.php'); ?> <!-- Include Header/Navbar -->

<div class="container mt-4">
    <h2>Payment for Booking #<?php echo $booking['BookingID']; ?></h2>
    <p>Room No: <?php echo $booking['RoomNo']; ?></p>
    <p>Room Type: <?php echo $booking['room_type']; ?></p>
    <p>Check-in Date: <?php echo $booking['checkInDate']; ?></p>
    <p>Check-out Date: <?php echo $booking['checkOutDate']; ?></p>
    <p>Total Amount: <?php echo $booking['total_amount']; ?> BDT</p>

    <form method="POST" action="payment.php?bookingID=<?php echo $booking['BookingID']; ?>">
        <div class="form-group">
            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
                <option value="">Select Payment Method</option>
                <option value="Cash">Cash</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Bkash">Bkash</option>
                <option value="Nogod">Nogod</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success mt-3">Proceed with Payment</button>
    </form>
</div>

<?php include('footer.php'); ?> <!-- Include Footer -->

</body>
</html>
