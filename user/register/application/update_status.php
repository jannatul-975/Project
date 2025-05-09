<?php
include('../../../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if necessary data is provided
    if (isset($_POST['action']) && isset($_POST['applicationId'])) {
        $action = $_POST['action'];
        $applicationId = $_POST['applicationId'];

        if ($action === 'approve') {

            // Update the application status to 'Approved' and store the verification code
            $query = "UPDATE application SET status = 'Approved' WHERE applicationId = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $applicationId);
            $stmt->execute();

            // Check if update was successful
            if ($stmt->affected_rows > 0) {
                // Return the success response with the generated code
                echo json_encode([
                    'status' => 'approved',
                    'message' => 'Application has been approved successfully!'
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to approve the application.']);
            }
        } elseif ($action === 'reject') {
            // Update the application status to 'Rejected'
            $query = "UPDATE application SET status = 'Rejected' WHERE applicationId = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $applicationId);
            $stmt->execute();

            // Check if update was successful
            if ($stmt->affected_rows > 0) {
                // Return the success response
                echo json_encode([
                    'status' => 'rejected',
                    'message' => 'Application has been rejected successfully!'
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to reject the application.']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

?>
