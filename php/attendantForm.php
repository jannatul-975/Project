<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Define the table name
    $table = 'attendant'; // Set the correct table name here

    // Ensure required fields are set
    if (!isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'])) {
        echo "❌ Missing required fields.";
        exit;
    }

    // Sanitize input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);

    // Set default values
    $defaultPassword = password_hash("guesthouse@!", PASSWORD_DEFAULT);
    $defaultProfilePic = '/project/profile_pics/profile.jpg'; // Default profile picture path

    // Deactivate previously active attendant, if any
    $conn->query("UPDATE `$table` SET is_active = 0");

    // Prepare insert for new active attendant
    $stmt = $conn->prepare("INSERT INTO `$table` (name, email, phone, address, profile_pic, password, is_active) 
                            VALUES (?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $address, $defaultProfilePic, $defaultPassword);

    if ($stmt->execute()) {
        echo "✅ Attendant profile created and set as active.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
