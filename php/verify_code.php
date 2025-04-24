<?php
include('dbconnect.php'); // Include the database connection file

// Start the session to get the session variables
session_start();

// Get the verification code from the POST request
$verification_code = $_POST['verification_code'] ?? '';
$user_id = $_SESSION['id'] ?? '';  // Get user_id from the session

// If verification code or user_id is not provided, return an error
if (empty($verification_code) || empty($user_id)) {
    echo "invalid";
    exit;
}

// Step 1: Fetch the most recent application_id from the teacher_application table based on the user_id
$stmt = $conn->prepare("SELECT ApplicationID FROM teacher_application WHERE TeacherID = ? ORDER BY submission_date DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($application_id);
$stmt->fetch();
$stmt->close();

// Check if the application_id was found
if (empty($application_id)) {
    echo "invalid"; // If no application was found for the user, return invalid
    exit;
}

// Step 2: Check if the verification code exists and is associated with the application_id and is not used
$stmt = $conn->prepare("SELECT * FROM verification_code WHERE application_id = ? AND code = ? AND is_used = 0 LIMIT 1");
$stmt->bind_param("is", $application_id, $verification_code);
$stmt->execute();
$result = $stmt->get_result();

// If the code exists and is unused, mark it as used
if ($result->num_rows > 0) {
    // Code is valid, mark it as used
    $stmt = $conn->prepare("UPDATE verification_code SET is_used = 1 WHERE application_id = ? AND code = ?");
    $stmt->bind_param("is", $application_id, $verification_code);
    $stmt->execute();

    echo "valid";  // Return "valid" to indicate the code is correct
} else {
    echo "invalid";  // Return "invalid" if the code doesn't exist or is already used
}

$stmt->close();
?>
