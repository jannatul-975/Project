<?php
include('dbconnect.php');

// Get the filter values from POST request
$statusFilter = $_POST['status'] ?? '';
$search = $_POST['search'] ?? '';

// Build the query to fetch the application data with joins for teacher name and room number
$query = "
    SELECT ta.ApplicationID, ta.TeacherID, ta.guest_information, ta.checkin_date, ta.checkout_date, ta.status, 
           ta.submission_date, t.name AS teacher_name, r.RoomNo AS room_number
    FROM teacher_application ta
    JOIN teacher t ON ta.TeacherID = t.id
    JOIN room r ON ta.room_id = r.RoomID
    WHERE 1";

if ($statusFilter) {
    $query .= " AND ta.status = '$statusFilter'";
}

if ($search) {
    $query .= " AND (ta.guest_information LIKE '%$search%' OR t.name LIKE '%$search%' OR ta.checkin_date LIKE '%$search%' OR ta.checkout_date LIKE '%$search%' OR ta.submission_date LIKE '%$search%')";
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Teacher Name</th>
                    <th>Room No</th>
                    <th>Guest Information</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = $result->fetch_assoc()) {
        $applicationID = $row['ApplicationID'];
        $teacherName = $row['teacher_name'];
        $roomNumber = $row['room_number'];
        $guestInformation = $row['guest_information'];
        $checkinDate = $row['checkin_date'];
        $checkoutDate = $row['checkout_date'];
        $submissionDate = $row['submission_date'];
        $status = $row['status'];

        echo "<tr>
                <td>{$teacherName}</td>
                <td>{$roomNumber}</td>
                <td>{$guestInformation}</td>
                <td>{$checkinDate}</td>
                <td>{$checkoutDate}</td>
                <td>{$submissionDate}</td>
                <td>{$status}</td>
                <td>
                    <button class='btn btn-success approve-btn' data-id='{$applicationID}'>Approve</button>
                    <button class='btn btn-danger reject-btn' data-id='{$applicationID}'>Reject</button>
                </td>
              </tr>";
    }

    echo '</tbody></table>';
} else {
    echo "<p>No applications found</p>";
}
?>

<script>
// Handle Approve and Reject buttons

// Handle Approve and Reject buttons
$(document).on('click', '.approve-btn', function () {
    const applicationID = $(this).data('id');

    $.ajax({
        url: 'process_application.php',
        method: 'POST',
        data: { action: 'approve', applicationID: applicationID },
        success: function(response) {
            alert(response);  // Display the success message
            fetchApplications();  // Refresh the applications list after approval
        },
        error: function() {
            alert("Error occurred while processing the application.");
        }
    });
});

$(document).on('click', '.reject-btn', function () {
    const applicationID = $(this).data('id');

    $.ajax({
        url: 'process_application.php',
        method: 'POST',
        data: { action: 'reject', applicationID: applicationID },
        success: function(response) {
            alert(response);  // Display the success message
            fetchApplications();  // Refresh the applications list after rejection
        },
        error: function() {
            alert("Error occurred while rejecting the application.");
        }
    });
});

</script>

