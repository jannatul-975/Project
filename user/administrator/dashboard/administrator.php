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
    <div class="container" style="margin-top: 20px;margin-left: 250px;">
        <h3>Administrator Dashboard</h3>
        <div class="sitemap-box" style="margin-top: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
            <h5 class="sitemap_header">Sitemap of a Administrator</h5>
            <ol class="sitemap_list" style="list-style-type: decimal; padding-left: 20px;">
                <li><a href="../php/administrator.php">Dashboard</a>: Access your personal dashboard.</li>
                <li><a href="../php/ad_booking.php">Booking </a>: Book a guest room in guest house.</li>
                <li><a href="../php/booking_history.php">Booking History</a>: View  booking history.</li>
                <li><a href="../php/payment_history.php">Payment History</a>: View your payment records.</li>
                <li><a href="../php/giving_feedback.php">Giving feedback</a>: Give Feedbacks on experiencing in guest house of Khulna University.</li>
                <li><a href="../php/ad_Profile.php">Profile</a>: View your Profile Information.</li>
                <li><a href="../php/logout.php">logout</a>: Logout from your profile.</li>
            </ol>
        </div>
    </div>

<?php
include('ad_footer.php');
?>
