<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../db.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values
    $room_name = $_POST['room_name'];
    $room_type = $_POST['room_type']; // Room type selected from the dropdown
    $price_per_night = $_POST['price_per_night'];
    $status = $_POST['status'];

    // Validate inputs
    if (empty($room_name) || empty($room_type) || empty($price_per_night) || empty($status)) {
        $error = "Please fill in all fields.";
    } else {
        // Insert into room table
        $stmt = $conn->prepare("INSERT INTO room (RoomName, room_type, pricePerNight, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $room_name, $room_type, $price_per_night, $status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $success = "Room added successfully!";
        } else {
            $error = "Failed to add room.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <h3>Add Room</h3>

        <!-- Display success/error messages -->
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

        <form action="insertRoom.php" method="POST">
            <div class="form">
                <label for="room_name">Room Name</label>
                <input type="text" id="room_name" name="room_name" class="form-control" required />
            </div>

            <div class="form">
                <label for="room_type">Room Type</label>
                <select id="room_type" name="room_type" class="form-control" required>
                    <option value="">Select Room Type</option>
                    <option value="VIP">VIP</option>
                    <option value="AC">AC</option>
                    <option value="Non AC single">Non AC single</option>
                    <option value="Non AC double">Non AC double</option>
                </select>
            </div>

            <div class="form">
                <label for="price_per_night">Price per Night (BDT)</label>
                <select id="price_per_night" name="price_per_night" class="form-control" required>
                    <option value="">Select Price</option>
                    <option value="220">220</option>
                    <option value="440">440</option>
                </select>
            </div>

            <div class="form">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Available">Available</option>
                    <option value="Under Maintenance">Under Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="submitButton">Add Room</button>
            </div>
        </form>
    </div>
</body>
</html>
