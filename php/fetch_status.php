<?php
include('dbconnect.php');

// Start the session
session_start();

// Get the current logged-in teacher's ID
$teacher_id = $_SESSION['id'] ?? 0;

// If the user is not logged in or teacher_id is missing, return an error message
if (!$teacher_id) {
    echo "Unauthorized access!";
    exit;
}

// Get the filter and search values from POST request
$statusFilter = $_POST['status'] ?? '';  // Filter by status (Approved, Pending, Rejected)
$search = $_POST['search'] ?? '';        // Search query for Guest Name or Date

// Base query to fetch applications
$query = "
    SELECT ta.ApplicationID, ta.TeacherID, ta.guest_information, ta.checkin_date, ta.checkout_date, ta.status, 
           ta.submission_date, t.name AS teacher_name, r.RoomNo AS room_number, vc.code AS verification_code
    FROM teacher_application ta
    JOIN teacher t ON ta.TeacherID = t.id
    JOIN room r ON ta.room_id = r.RoomID
    LEFT JOIN verification_code vc ON ta.ApplicationID = vc.application_id
    WHERE ta.TeacherID = ?";  // Only fetch applications for the logged-in teacher

// Apply filters
if ($statusFilter) {
    $query .= " AND ta.status = ?";
}

if ($search) {
    $query .= " AND (ta.guest_information LIKE ? OR ta.checkin_date LIKE ? OR ta.checkout_date LIKE ?)";
}

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind parameters
if ($statusFilter && $search) {
    $searchParam = "%$search%";
    $stmt->bind_param("isss", $teacher_id, $statusFilter, $searchParam, $searchParam);
} elseif ($statusFilter) {
    $stmt->bind_param("is", $teacher_id, $statusFilter);
} elseif ($search) {
    $searchParam = "%$search%";
    $stmt->bind_param("is", $teacher_id, $searchParam);
} else {
    $stmt->bind_param("i", $teacher_id); // Only bind teacher ID
}

$stmt->execute();
$result = $stmt->get_result();

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
                    <th>Action</th>
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
        $verificationCode = $row['verification_code'];  // Get the verification code if available

        echo "<tr>
                <td>{$teacherName}</td>
                <td>{$roomNumber}</td>
                <td>{$guestInformation}</td>
                <td>{$checkinDate}</td>
                <td>{$checkoutDate}</td>
                <td>{$submissionDate}</td>
                <td>{$status}</td>
                <td>";

        // Show actions based on application status
        if ($status === 'Approved') {
            // Display the verification code if application is approved
            echo $verificationCode ? "<span class='text-success'>{$verificationCode}</span>" : "<span class='text-muted'>No Code</span>";
        } elseif ($status === 'Rejected') {
            // Display "Rejected" text
            echo '<span class="text-danger">Rejected</span>';
        } else {
            // For Pending status, show approve and reject buttons
            echo '<button class="btn btn-success approve-btn" data-id="'.$applicationID.'">Approve</button>';
            echo '<button class="btn btn-danger reject-btn" data-id="'.$applicationID.'">Reject</button>';
        }

        echo "</td></tr>";
    }

    echo '</tbody></table>';
} else {
    echo "<p>No applications found</p>";
}
?>
