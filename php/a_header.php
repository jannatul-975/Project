<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db.php');

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch active attendant details from the database using the session ID
$a_id = $_SESSION['id'];
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
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="bg-dark text-white p-3">
        <h4 class="text-center">Khulna University</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="../php/attendant.php" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="../php/guest_history.php" class="nav-link">Guest Information</a></li>
            <li class="nav-item"><a href="../php/payment_history.php" class="nav-link">Payment History</a></li>
            <li class="nav-item"><a href="../php/roomInformation.php" class="nav-link">Rooms</a></li>
            <li class="nav-item"><a href="../php/staffInformation.php" class="nav-link">Staffs</a></li>
            <li class="nav-item"><a href="../php/a_Profile.php" class="nav-link">Profile</a></li>
            <li class="nav-item"><a href="../php/logout.php" class="nav-link">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="content" class="p-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="navbar-text">
                    Welcome, <span id="attendant-name"><?php echo htmlspecialchars($attendant['name']); ?></span>!
                </div>
                <div class="dropdown">
                    <img src="../profile_pics/<?php echo htmlspecialchars($attendant['profile_pic'] ?: 'profile.jpg'); ?>" class="rounded-circle" width="40" height="40" id="profile-btn" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu dropdown-menu-end" id="view-profile">
                        <li><a class="dropdown-item" href="../php/a_Profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../php/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

    