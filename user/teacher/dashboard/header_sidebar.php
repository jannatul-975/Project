<?php
// Start the session only if it is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../../../db.php'); // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch teacher info from the database
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "<script>alert('Active user not found. Please contact admin or log in again.');</script>";
    exit();
}

// Get profile picture or use a default one
$profile_pic = isset($user['profile_pic']) && !empty($user['profile_pic']) ? $user['profile_pic'] : 'profile.jpg';
$user_name = isset($user['name']) ? $user['name'] : 'N/A';
?>
<link rel="stylesheet" href="../../../dashboard.css">
<!-- Sidebar -->
<nav id="sidebar" class="sidebar_content" style="width: 250px; position: fixed; height: 100%; padding: 20px; z-index: 1000;">
    <h4 class="text-center">Khulna University</h4>
    <ul class="sidebar_nav">
        <li class="nav-item">
            <a href="../dashboard/teacher.php" class="nav-link" id="dashboard-link">Dashboard</a>
        </li>
        <li class="nav-item">
            <a href="../application/applicationForm.php" class="nav-link" id="application-form-link">Send Application</a>
        </li>
        <li class="nav-item">
            <a href="../book/status.php" class="nav-link" id="status-link">Status</a>
        </li>
        <li class="nav-item">
            <a href="../book/booking.php" class="nav-link" id="booking-link">Booking</a>
        </li>
        <li class="nav-item">
            <a href="../php/booking_history.php" class="nav-link" id="booking-history-link">Booking History</a>
        </li>
        <li class="nav-item">
            <a href="../php/payment_history.php" class="nav-link" id="payment-history-link">Payment History</a>
        </li>
        <li class="nav-item"><a href="../feedback/feedback.php" class="nav-link">Giving Feedback</a></li>
        <li class="nav-item"><a href="../php/teacherProfile.php" class="nav-link">Profile</a></li>
        <li class="nav-item"><a href="../../logout.php" class="nav-link">Logout</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div id="content" class="nav_container" style="margin-left: 250px; padding: 20px;">
    <nav class="navbarStyle" style="background-color: #f8f9fa; padding: 10px; border-bottom: 1px solid #ccc;">
        <div class="navbar">
            <!-- Welcome Text on Left Side -->
            <div class="navbar-text">
                Welcome, <span id="teacher-name"><?php echo $user_name; ?></span>!
            </div>
            
            <!-- Profile Image on the Right Side -->
            <div class="navbar-right">
                <div class="dropdown">
                    <img 
                        src="../../profile_pics/<?php echo $profile_pic; ?>" 
                        class="rounded-circle" 
                        width="40" 
                        height="40" 
                        id="profile-btn" 
                        data-bs-toggle="dropdown"/>
                    <ul class="dropdown-menu" id="view-profile">
                        <li><a class="dropdown-item" href="../dashboard/teacher.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../../../login.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>
