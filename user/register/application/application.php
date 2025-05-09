<?php
include('../../../db.php');

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the application ID from the URL
if (isset($_GET['id'])) {
    $applicationId = $_GET['id'];
} else {
    echo "Application ID is missing!";
    exit;
}

// Query to fetch application details based on application ID
$query = "
    SELECT 
        ta.applicationId, 
        ta.userId, 
        ta.status, 
        ta.submission_date, 
        ta.checkInDate, 
        ta.checkOutDate, 
        ta.purpose, 
        ta.guestInformation,  
        tu.name AS Name,  
        tu.dept_name AS discipline,
        tu.designation AS designation,
        tu.phone AS phone
    FROM application ta 
    JOIN user tu ON ta.userId = tu.id
    WHERE ta.applicationId = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $applicationId);  // Bind the application ID
$stmt->execute();
$result = $stmt->get_result();

// Check if the application exists
if ($result->num_rows > 0) {
    $application = $result->fetch_assoc();
} else {
    echo "Application not found!";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Application for Guest House</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .application-letter {
  background-color: #ffffff;
  border: 1px solid #ced4da;
  padding: 25px;
  margin: 30px auto;
  max-width: 800px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-radius: 8px;
  line-height: 1.7;
  font-size: 16px;
  color: #212529;
}

.application-letter h5 {
  margin-bottom: 10px;
  font-weight: 600;
}

.application-letter p {
  margin-bottom: 12px;
}
h5{
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
}
.application-actions {
  max-width: 800px;
  margin: 10px auto 30px auto;
  text-align: right;
}

.application-actions button {
  margin-left: 10px;
  padding: 8px 20px;
  font-size: 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-success {
  background-color: #28a745;
  color: white;
}

.btn-success:hover {
  background-color: #218838;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
}

.btn-danger:hover {
  background-color: #c82333;
}
.status-message{
    max-width: 800px;
    margin: 10px auto 30px auto;
    text-align: right;
    font_size: 30px;
    font-weight: bold;
}


    </style>
</head>
<body>
<?php include('../dashboard/r_header.php'); ?>
<div class="container">
     

    <div class="application-letter">
        <h5><?php echo $application['discipline']; ?> Discipline</h5>
        <h5><strong>Khulna University</strong></h5>
        <p><strong>Date: </strong> <?php echo date('d-m-Y', strtotime($application['submission_date'])); ?></p>
        <p>To,</p>
        <p><strong>Register Sir,</strong></p> 
        <p><strong>Maikel Modhusudon Dotto Guest House</strong></p>
        <p><strong>Khulna University, Khulna</strong></p>

        <p><strong>Subject: Application for Booking Room in Guest House</strong></p>

        <p>Sir,</p>

        <p>With respect, I would like to inform you that <?php echo ($application['guestInformation'] ? 'my guest ' . $application['guestInformation'] : 'I myself'); ?> would like to stay at the Khulna University Guest House during the period of <?php echo date('d-m-Y', strtotime($application['checkInDate'])); ?> to <?php echo date('d-m-Y', strtotime($application['checkOutDate'])); ?>.</p>

        <p>In this regard, I humbly request your permission to stay at the guest house during the mentioned dates.</p>

        <p>Sincerely,</p>

        <p><strong><?php echo $application['Name']; ?></strong></p>
        <p><?php echo $application['designation']; ?></p>
        <p><?php echo $application['discipline']; ?></p>
        <p><strong>Khulna University</strong></p>
        <?php echo $application['phone']; ?></p>
    </div>
</div>

<!-- Show buttons only if status is Pending -->
<div class="application-actions" id="actionBtns">
    <?php if ($application['status'] == 'Pending') { ?>
        <button type="button" id="approveBtn" class="btn btn-success">Approve</button>
        <button type="button" id="rejectBtn" class="btn btn-danger">Reject</button>
    <?php } ?>
</div>

<!-- Show Status Message -->
<div id="statusMessage" class="status-message">
    <?php if ($application['status'] == 'Approved') { ?>
        <p><span style="color: green;">Approved</span></p>
    <?php } elseif ($application['status'] == 'Rejected') { ?>
        <p><span style="color: red;">Rejected</span></p>
    <?php } ?>
</div>

<script>
$(document).ready(function() {
    // Handle the approve button click
    $('#approveBtn').click(function() {
        $.ajax({
            url: '../application/update_status.php', // PHP file that processes the status update
            type: 'POST',
            data: {
                action: 'approve',
                applicationId: <?php echo $application['applicationId']; ?>
            },
            success: function(response) {
                // Handle the response, update the UI
                alert(response.message);
                if (response.status === 'approved') {
                    $('#applicationStatus').text('Approved').css('color', 'green');
                    $('#statusMessage').html('<p>Status: <span style="color: green;">Approved</span></p>');
                    $('#approveBtn').hide(); // Hide the approve button
                    $('#rejectBtn').hide(); // Hide the reject button
                }
            }
        });
    });

    // Handle the reject button click
    $('#rejectBtn').click(function() {
        $.ajax({
            url: '../application/update_status.php', // PHP file that processes the status update
            type: 'POST',
            data: {
                action: 'reject',
                applicationId: <?php echo $application['applicationId']; ?>
            },
            success: function(response) {
                // Handle the response, update the UI
                alert(response.message);
                if (response.status === 'rejected') {
                    $('#statusMessage').html('<p>Status: <span style="color: red;">Rejected</span></p>');
                    $('#approveBtn').hide(); // Hide the approve button
                    $('#rejectBtn').hide(); // Hide the reject button
                }
            }
        });
    });
});
</script>
</body>
</html>
