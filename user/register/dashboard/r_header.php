<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../../../db.php'); // Ensure correct path for dbconnect.php

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the user table using the session ID
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?"); // Removed is_active check
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Fetch the user data
$stmt->close();

// Check if user was found and if they have the 'Register' role
if (!$user || $user['role'] !== 'Register') {
    echo "<script>alert('User not found or unauthorized access.'); window.location.href = 'login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar_content">
        <h4 class="text-center">Khulna University</h4>
        <ul class="sidebar_nav">
            <li class="nav-item"><a href="../dashboard/register.php" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="../application/applications.php" class="nav-link">Applications</a></li>
            <li class="nav-item"><a href="../book/booking.php" class="nav-link">Booking</a></li>
            <li class="nav-item"><a href="../php/r_booking_history.php" class="nav-link">Booking History</a></li>
            <li class="nav-item"><a href="../php/payment_history.php" class="nav-link">Payment History</a></li>
            <li class="nav-item"><a href="../php/giving_feedback.php" class="nav-link">Giving Feedback</a></li>
            <li class="nav-item"><a href="../php/registerProfile.php" class="nav-link">Profile</a></li>
            <li class="nav-item"><a href="../../../logout.php" class="nav-link">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="content" class="nav_container">
        <nav class="navbarStyle">
            <div class="navbar">
                <div class="navbar-text">
                    Welcome, <span id="register-name"><?php echo htmlspecialchars($user['name']); ?></span>!
                </div>
                <div class="dropdown">
                    <img src="../../profile_pics/<?php echo htmlspecialchars($user['profile_pic'] ?: 'profile.jpg'); ?>" class="rounded-circle" width="40" height="40" id="profile-btn" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu" id="view-profile">
                        <li><a class="dropdown-item" href="../php/registerProfile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../../../login.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
