<?php
session_start();
include('db.php');

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch attendant details from the database using the session ID
$attendant_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM attendant WHERE id = ?");
$stmt->bind_param("i", $attendant_id);
$stmt->execute();
$result = $stmt->get_result();
$attendant = $result->fetch_assoc();  
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendant Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include('a_header.php'); ?>
        <div class="container mt-4">
            <h3>Attendant Dashboard</h3>
            <div class="sitemap-box">
                <h5 class="text-white p-2">Sitemap of an Attendant</h5>
                <ol>
                    <li><a href="../php/attendant.php">Dashboard</a>: Access your personal dashboard.</li>
                    <li><a href="../php/guest_history.php">Guest Information</a>: View Guest information.</li>
                    <li><a href="../php/payment_history.php">Payment History</a>:view and manage payment information.</li>
                    <li><a href="../php/roomInformation.php">Rooms</a>: View and manage room information.</li>
                    <li><a href="../php/staffInformation.php">Staffs</a>: View and manage staff information.</li>
                </ol>
            </div>
        </div>
    
    <?php include('a_footer.php'); ?>