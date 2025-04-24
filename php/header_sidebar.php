<?php
// Start the session only if it is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch teacher info from the database
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();
$stmt->close();

if (!$teacher) {
    echo "<script>alert('Active user not found. Please contact admin or log in again.');</script>";
    exit();
}

// Get profile picture or use a default one
$profile_pic = isset($teacher['profile_pic']) && !empty($teacher['profile_pic']) ? $teacher['profile_pic'] : 'profile.jpg';
$user_name = isset($teacher['name']) ? $teacher['name'] : 'N/A';
?>

<!-- Sidebar -->
<nav id="sidebar" class="bg-dark text-white p-3">
    <h4 class="text-center">Khulna University</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="../php/teacher.php" class="nav-link" id="dashboard-link">Dashboard</a>
        </li>
        <li class="nav-item">
            <a href="../php/applicationForm.php" class="nav-link" id="application-form-link">Send Application</a>
        </li>
        <li class="nav-item">
            <a href="../php/status.php" class="nav-link" id="status-link">Status</a>
        </li>
        <li class="nav-item">
            <a href="../php/booking.php" class="nav-link" id="booking-myself-link">Booking</a>
        </li>
        <li class="nav-item">
            <a href="../php/s_booking.php" class="nav-link" id="booking-myself-link">Booking Update</a>
        </li>
        <li class="nav-item">
            <a href="../php/booking_history.php" class="nav-link" id="booking-history-link">Booking History</a>
        </li>
        <li class="nav-item">
            <a href="../php/payment_history.php" class="nav-link" id="payment-history-link">Payment History</a>
        </li>
        <li class="nav-item"><a href="../php/giving_feedback.php" class="nav-link">Giving Feedback</a></li>
        <li class="nav-item"><a href="../php/teacherProfile.php" class="nav-link">Profile</a></li>
        <li class="nav-item"><a href="../php/logout.php" class="nav-link">Logout</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div id="content" class="p-4" style="margin-left: 250px;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Welcome Text on Left Side -->
            <div class="navbar-text">
                Welcome, <span id="teacher-name"><?php echo $user_name; ?></span>!
            </div>
            
            <!-- Profile Image on the Right Side -->
            <div class="navbar-right">
                <div class="dropdown">
                    <img 
                        src="../profile_pics/<?php echo $profile_pic; ?>" 
                        class="rounded-circle" 
                        width="40" 
                        height="40" 
                        id="profile-btn" 
                        data-bs-toggle="dropdown" 
                    />
                    <ul class="dropdown-menu dropdown-menu-end" id="view-profile">
                        <li><a class="dropdown-item" href="../php/teacherProfile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../php/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>
