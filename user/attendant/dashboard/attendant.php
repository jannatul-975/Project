<?php include('a_header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendant Dashboard</title>
    <!-- <link rel="stylesheet" href="../css/dashboard.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
        <div class="container">
            <div class="dashboard"><h4>Sitemap of an Attendant</h4></div>
            <div class="sitemap-box" style="margin-top: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
                <ol  class="sitemap_list" style="list-style-type: decimal; padding-left: 20px;">
                    <li><a href="../dashboard/attendant.php">Dashboard</a>: Access your personal dashboard.</li>
                    <li><a href="../history/booking_history.php">Guest Information</a>: View Guest information.</li>
                    <li><a href="../history/payment_history.php">Payment History</a>:view payment information and generate report.</li>
                    <li><a href="../paymentEntry/make_payment.php">Add Payment</a>:view and add payment.</li>
                    <li><a href="../php/roomInformation.php">Rooms</a>: View and manage room information.</li>
                    <li><a href="../php/staffInformation.php">Staffs</a>: View and manage staff information.</li>
                </ol>
            </div>
        </div>
    
    <?php include('a_footer.php'); ?>