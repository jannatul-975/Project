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

// Initialize filter and search variables
$statusFilter = $_GET['status'] ?? ''; // Get filter for status (paid, pending)
$checkoutSearch = $_GET['checkout'] ?? ''; // Get search value for checkout date

// Query to fetch the teacher's booking history with filters
$query = "
    SELECT b.BookingID, b.RoomNo, b.checkInDate, b.checkOutDate, b.total_amount, b.status, r.room_type
    FROM booking b
    JOIN room r ON b.RoomNo = r.RoomID
    WHERE b.booked_by_id = ? AND b.booked_by_role = 'teacher'"; // Ensure it's only fetching bookings for teachers

// Add filters to the query
if ($statusFilter) {
    $query .= " AND b.status = ?";
}

if ($checkoutSearch) {
    $query .= " AND b.checkOutDate = ?";
}

$stmt = $conn->prepare($query);

// Bind parameters based on filter or search criteria
if ($statusFilter && $checkoutSearch) {
    $stmt->bind_param("iss", $user_id, $statusFilter, $checkoutSearch);
} elseif ($statusFilter) {
    $stmt->bind_param("is", $user_id, $statusFilter);
} elseif ($checkoutSearch) {
    $stmt->bind_param("is", $user_id, $checkoutSearch);
} else {
    $stmt->bind_param("i", $user_id); // Bind only the teacher's ID
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="teacher.css" />
</head>
<body>

<?php include('header_sidebar.php'); ?> <!-- Include Header/Navbar -->

<div class="container mt-4">
    <h2>Your Booking History</h2>

    <!-- Filter and Search Form -->
    <form method="GET" action="booking_history.php" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">Filter by Status</option>
                    <option value="Paid" <?php echo ($statusFilter == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                    <option value="Pending" <?php echo ($statusFilter == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="checkout" class="form-control" value="<?php echo htmlspecialchars($checkoutSearch); ?>" />
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </form>

    <?php if ($result->num_rows > 0) { ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Room No</th>
                    <th>Room Type</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    $status = $row['status'];
                    $bookingID = $row['BookingID'];
                    $total_amount = $row['total_amount'] ? $row['total_amount'] : 'N/A';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['RoomNo']); ?></td>
                        <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['checkInDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['checkOutDate']); ?></td>
                        <td><?php echo htmlspecialchars($total_amount); ?></td>
                        <td><?php echo htmlspecialchars($status); ?></td>
                        <td>
                            <?php if ($status === 'Pending') { ?>
                                <a href="payment.php?bookingID=<?php echo $bookingID; ?>" class="btn btn-primary">Process Payment</a>
                            <?php } else {
                                echo 'Paid';
                            } ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No booking history found. Please make a booking first.</p>
    <?php } ?>
</div>

<?php include('footer.php'); ?> <!-- Include Footer -->

</body>
</html>
