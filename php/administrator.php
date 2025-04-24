<?php
include('ad_header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Administrator Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Optional Custom JS for dynamic behavior (like dropdown, etc.) -->
    <script src="teacher.js"></script>
</head>
<body>
    <!-- Main Content Specific to Register -->
    <div class="container mt-4">
        <h3>Administrator Dashboard</h3>
        <div class="sitemap-box">
            <h5 class="text-white p-2">Sitemap of a Administrator</h5>
            <ol>
                <li><a href="administrator.php">Dashboard</a>: Access your personal dashboard.</li>
                <li><a href="ad_booking.php">Booking </a>: Book a guest room in guest house.</li>
                <li><a href="booking_history.php">Booking History</a>: View  booking history.</li>
                <li><a href="payment_history.php">Payment History</a>: View your payment records.</li>
                <li><a href="giving_feedback.php">Giving feedback</a>: Give Feedbacks on experiencing in guest house of Khulna University.</li>
                <li><a href="ad_Profile.php">Profile</a>: View your Profile Information.</li>
                <li><a href="logout.php">logout</a>: Logout from your profile.</li>
            </ol>
        </div>
    </div>

<?php
include('ad_footer.php');
?>
