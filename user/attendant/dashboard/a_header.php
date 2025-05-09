<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../../../db.php');

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch active attendant details from the database using the session ID
$a_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM attendant WHERE id = ? AND is_active = 1");
$stmt->bind_param("i", $a_id);
$stmt->execute();
$result = $stmt->get_result();
$attendant = $result->fetch_assoc(); // This should be $attendant
$stmt->close();

if (!$attendant) {  // Check if attendant is fetched correctly
    echo "<script>alert('Active user not found. Please contact admin or log in again.');</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendant Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        h3{
            color:rgb(7, 53, 98); /* Primary Blue */
            font-weight: bold;
        }
        h5{
            color:rgb(7, 53, 98); /* Primary Blue */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar_content">
        <h4 class="text-center">Khulna University</h4>
        <ul class="sidebar_nav">
            <li class="nav-item"><a href="../dashboard/attendant.php" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="../history/booking_history.php" class="nav-link">Guest Information</a></li>
            <li class="nav-item"><a href="../history/payment_history.php" class="nav-link">Payment History</a></li>
            <li class="nav-item"><a href="../paymentEntry/make_payment.php" class="nav-link">Add Payment</a></li>
            <li class="nav-item"><a href="../history/payment_record.php" class="nav-link">Rooms</a></li>
            <li class="nav-item"><a href="../php/staffInformation.php" class="nav-link">Staffs</a></li>
            <li class="nav-item"><a href="../php/a_Profile.php" class="nav-link">Profile</a></li>
            <li class="nav-item"><a href="../../../logout.php" class="nav-link">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="content" class="nav_container">
        <nav class="navbarStyle">
            <div class="navbar">
                <div class="navbar-text">
                    <h3>Welcome, <span id="attendant-name"><?php echo htmlspecialchars($attendant['name']); ?></span>!</h3>
                    <h5>Attendant of Mikel Modhusudon Datta Guest House, Khulna University</h5>
                </div>
                <div class="dropdown">
                    <img src="../../profile_pics/<?php echo htmlspecialchars($attendant['profile_pic'] )?>" class="rounded-circle" width="50" height="50" id="profile-btn" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu " id="view-profile">
                        <li><a class="dropdown-item" href="../php/a_Profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../../../logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

    