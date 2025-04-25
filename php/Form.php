<?php
include('db.php');

// Check if the form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if required fields are set
    if (!isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['designation'], $_POST['dept_name'], $_POST['role'])) {
        echo "❌ Missing required fields.";
        exit;
    }

    // Sanitize inputs to prevent SQL injection
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $designation = $conn->real_escape_string($_POST['designation']);
    $dept_name = $conn->real_escape_string($_POST['dept_name']);
    $role = $conn->real_escape_string($_POST['role']);

    // Default password for the user
    $defaultPassword = password_hash("reyadonekvalo", PASSWORD_DEFAULT);
    // Default profile picture path (this can be omitted as it is set in the database schema)
    $defaultProfilePic = '/project/profile_pics/profile.jpg'; 

    // Insert the new user into the user table (without the 'id' column since it's auto-incremented)
    $stmt = $conn->prepare("INSERT INTO user (name, email, phone, role, dept_name, designation, password, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $email, $phone, $role, $dept_name, $designation, $defaultPassword, $defaultProfilePic);

    if ($stmt->execute()) {
        echo "✅ User profile created successfully.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>