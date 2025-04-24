<?php
include('db.php');

// Check if the user is authorized
session_start();
$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['id'] ?? 0;

if (!$role || !$user_id || $role !== 'register') {
    echo "Unauthorized access!";
    exit;
}

// Get the action and application ID
$action = $_POST['action'] ?? '';
$applicationID = $_POST['applicationID'] ?? 0;

if (!$applicationID || !$action) {
    echo "Invalid request.";
    exit;
}

// Process the action
if ($action == 'approve') {
    // Approve the application
    $updateQuery = "UPDATE teacher_application SET status = 'Approved' WHERE ApplicationID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $applicationID);
    if ($stmt->execute()) {
        // Generate a verification code and insert into the verification_code table
        $verificationCode = strtoupper(bin2hex(random_bytes(3)));  // Generate a random 6-character code

        $insertQuery = "INSERT INTO verification_code (application_id, code) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("is", $applicationID, $verificationCode);
        if ($stmt->execute()) {
            echo "Application approved. Verification code generated.";
        } else {
            echo "Failed to generate verification code.";
        }
    } else {
        echo "Failed to approve the application.";
    }
} elseif ($action == 'reject') {
    // Reject the application
    $updateQuery = "UPDATE teacher_application SET status = 'Rejected' WHERE ApplicationID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $applicationID);
    if ($stmt->execute()) {
        echo "Application rejected.";
    } else {
        echo "Failed to reject the application.";
    }
} else {
    echo "Invalid action.";
}
?>
