<?php
session_start();
include('../../../db.php');

// Check if the user is logged in and is a Teacher
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

// Fetch the logged-in user's information (Check if user role is Teacher)
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();
$stmt->close();

// Ensure the logged-in user is a teacher
if ($teacher['role'] !== 'Teacher') {
    echo "You are not authorized to submit an application.";
    exit();
}

// Handle form submission via POST (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_type = $_POST['booking_type'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $guest_information = $_POST['guest_information'] ?? NULL;  // Nullable guest information

    // Determine purpose based on booking type
    $purpose = ($booking_type === 'guest') ? 'Guest' : 'Myself';

    // Insert into application table
    $stmt = $conn->prepare("INSERT INTO application (userId,status,checkInDate, checkOutDate, purpose, guestInformation,is_booked) VALUES (?, 'Pending', ?, ?, ?, ?,0)");
    $stmt->bind_param("issss", $teacher_id, $checkin_date, $checkout_date, $purpose, $guest_information);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit the application.']);
    }

    $stmt->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Application Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <!-- Sidebar -->
    <?php include('../dashboard/header_sidebar.php'); ?>
      <div class="container" style="margin-top: 20px;margin-left: 250px;margin-right: 20px;">
        <h3>Send Application for Room Booking</h3>
        <form action="../application/applicationForm.php" method="POST" id="applicationForm">
          <div class="form">
            <label for="booking_type">Booking Type</label>
            <select id="booking_type" name="booking_type" class="form-control" required>
              <option value="myself">For Myself</option>
              <option value="guest">For Guest</option>
            </select>
          </div>

          <div class="form">
            <label for="checkin_date">Check-in Date</label>
            <input type="date" id="checkin_date" name="checkin_date" class="form-control" required />
          </div>

          <div class="form">
            <label for="checkout_date">Check-out Date</label>
            <input type="date" id="checkout_date" name="checkout_date" class="form-control" required />
          </div>

          <div class="form" id="guest_info" style="display: none">
            <label for="guest_information">Guest Information</label>
            <textarea
              id="guest_information"
              name="guest_information"
              class="form-control"
              placeholder="Enter guest information (optional)"
            ></textarea>
          </div>

          <div class="form">
            <button type="submit" class="submitButton">Submit Application</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      // Toggle guest info visibility based on booking type
      document.getElementById("booking_type").addEventListener("change", function () {
        const guestDiv = document.getElementById("guest_info");
        guestDiv.style.display = this.value === "guest" ? "block" : "none";
      });

      // Submit the form using AJAX
      $("#applicationForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
          url: "../application/applicationForm.php",
          type: "POST",
          data: $(this).serialize(),
          success: function (response) {
            alert("Application submitted successfully!");
            window.location.href = "../php/applicationForm.php";
          },
          error: function () {
            alert("An error occurred while submitting the application.");
          },
        });
      });
    </script>
 <?php include('../dashboard/footer.php'); ?>
  </body>
</html>
