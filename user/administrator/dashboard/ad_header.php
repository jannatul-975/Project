<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db.php'); // Ensure correct path for dbconnect.php

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$ad_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $ad_id);
$stmt->execute();
$result = $stmt->get_result();
$register = $result->fetch_assoc(); // Corrected variable to $register
$stmt->close();

// Check if the user exists and has the correct role
if (!$register) {
    echo "<script>alert('Active user not found. Please contact admin or log in again.'); window.location.href = 'login.php';</script>";
    exit();
}

// Check if the user role is one of the allowed roles (Vice Chancellor, Pro Vice Chancellor, or Treasurer)
$allowed_roles = ['Vice Chancellor', 'Pro Vice Chancellor', 'Treasurer'];
if (!in_array($register['role'], $allowed_roles)) {
    echo "<script>alert('Unauthorized access.'); window.location.href = 'login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrator Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar_content" style="width: 250px; position: fixed; height: 100%; background-color: #f8f9fa; padding: 20px; z-index: 1000;">
        <h4 class="text-center">Khulna University</h4>
        <ul class="sidebar_nav">
            <li class="nav-item"><a href="../php/administrator.php" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="../php/ad_booking.php" class="nav-link">Booking</a></li>
            <li class="nav-item"><a href="../php/booking_history.php" class="nav-link">Booking History</a></li>
            <li class="nav-item"><a href="../php/payment_history.php" class="nav-link">Payment History</a></li>
            <li class="nav-item"><a href="../php/giving_feedback.php" class="nav-link">Giving Feedback</a></li>
            <li class="nav-item"><a href="../php/ad_Profile.php" class="nav-link">Profile</a></li>
            <li class="nav-item"><a href="../php/logout.php" class="nav-link">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="content" class="nav_container" style="margin-left: 250px; padding: 20px;">
        <nav class="navbarStyle" style="background-color: #f8f9fa; padding: 10px; border-bottom: 1px solid #ccc;">
            <div class="navbar">
                <div class="navbar-text">
                    Welcome, <span id="ad-name"><?php echo htmlspecialchars($register['name']); ?></span>!
                </div>
                <div class="dropdown">
                    <img src="../profile_pics/<?php echo htmlspecialchars($register['profile_pic'] ?: 'profile.jpg'); ?>" class="rounded-circle" width="40" height="40" id="profile-btn" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu" id="view-profile">
                        <li><a class="dropdown-item" href="../php/ad_Profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Add additional content here -->
  
