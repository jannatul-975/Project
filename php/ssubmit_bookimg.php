<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('db.php');

// Check if the user is logged in
$role = $_SESSION['role'] ?? ''; // Get role from session
$user_id = $_SESSION['id'] ?? 0; // Get user ID from session

// If the role or user_id is not set, redirect to login
if (!$role || !$user_id) {
    echo "Unauthorized access!";
    header('Location: login.php'); // Redirect to login page
    exit;
}

// Fetch the department and university based on session data (user_id)
$query = "SELECT u.name AS university_name, d.name AS department_name 
          FROM users u 
          INNER JOIN departments d ON u.department_id = d.id
          INNER JOIN universities univ ON d.university_id = univ.id
          WHERE u.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user_id parameter
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Get the department and university names from the session data
$university_name = $row['university_name'];
$department_name = $row['department_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Room Booking Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="bookingStyle.css" />
    <link rel="stylesheet" href="teacher.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include('header_sidebar.php'); ?> <!-- Include Header/Navbar -->

<!-- Main Content for the Booking Form -->
<div class="container mt-4">
    <h2>Room Booking Form</h2>

    <form id="bookingForm">
        <!-- Booking Type -->
        <label for="booking_type">Booking Type</label>
        <select id="booking_type" name="booking_type" class="form-control" required>
            <option value="myself">Myself</option>
            <option value="academic">Academic Guest</option>
            <option value="non_academic">Non-Academic Guest</option>
        </select>

        <!-- Fields for "Myself" -->
        <div id="myselfFields" style="display: none;">
            <!-- University and department fields are removed for "Myself" -->
            <!-- Just hide the fields, but handle them in backend -->
        </div>

        <!-- Academic Guest Fields -->
        <div id="academicGuestFields" style="display: none;">
            <label for="guest_university">University Name</label>
            <select id="guest_university" name="guest_university" class="form-control" required>
                <!-- Universities will load dynamically -->
            </select>

            <label for="department_academic">Department Name</label>
            <select id="department_academic" name="department" class="form-control" required>
                <option value="">Select Department</option>
                <!-- Dynamic departments will be loaded here -->
            </select>
        </div>

        <!-- Non-Academic Guest Fields -->
        <div id="nonAcademicGuestFields" style="display: none;">
            <label for="guest_phone">Phone No</label>
            <input type="text" id="guest_phone" name="guest_phone" class="form-control" placeholder="Enter Guest's Phone Number" required />

            <label for="guest_address">Address</label>
            <input type="text" id="guest_address" name="guest_address" class="form-control" placeholder="Enter Address" required />

            <label for="other_info">Other Information (Optional)</label>
            <textarea id="other_info" name="other_info" class="form-control" placeholder="Enter any other information (Optional)"></textarea>
        </div>

        <!-- Common Fields for Check-in, Check-out, Room Type, etc. -->
        <label for="checkin">Check-in Date:</label>
        <input type="date" id="checkin" name="checkin" required />

        <label for="checkout">Check-out Date:</label>
        <input type="date" id="checkout" name="checkout" required />

        <label for="room_type">Room Type:</label>
        <select id="room_type" name="room_type">
            <option value="">All</option>
            <option value="VIP">VIP</option>
            <option value="AC">AC</option>
            <option value="Non AC & Double">Non AC & Double</option>
            <option value="Non AC & Single">Non AC & Single</option>
        </select>

        <label for="room">Available Rooms:</label>
        <select name="room[]" id="rooms" multiple required>
            <option value="">Select Check-in and Check-out Date</option>
        </select>

        <label for="days">Number of Nights</label>
        <input type="number" name="days" id="days" required>

        <label for="guestNumber">Number of Guests</label>
        <input type="number" name="guestNumber" id="guestNumber" required>

        <!-- Verification Code Field -->
        <label for="verification_code">Verification Code:</label>
        <input type="text" id="verification_code" name="verification_code" required placeholder="Enter verification code" />

        <input type="hidden" name="role" value="teacher">
        <input type="hidden" name="user_id" value="123"> <!-- Example user_id -->

        <button type="submit">Submit Booking</button>
    </form>

    <p id="message"></p>
</div>

<script>
// JavaScript logic to handle dynamic form changes (department options based on university and booking type)
$(document).ready(function () {
    // Set the default selection for "Myself" and show its fields
    const bookingType = $("#booking_type").val();  // Get the default selected value of booking type
    toggleBookingFields(bookingType);  // Call function to display relevant fields based on the default booking type

    // Toggle fields based on Booking Type selection
    $("#booking_type").change(function () {
        const bookingType = $(this).val();
        toggleBookingFields(bookingType);  // Update fields based on the selected booking type
    });

    // Function to show/hide relevant fields based on the selected booking type
    function toggleBookingFields(bookingType) {
        // Hide all fields first
        $("#myselfFields, #academicGuestFields, #nonAcademicGuestFields").hide();

        // Show fields based on selected booking type
        if (bookingType === 'myself') {
            $("#myselfFields").show();  // Show fields for "Myself"
            // Department and University fields are handled in the backend
        } else if (bookingType === 'academic') {
            $("#academicGuestFields").show();  // Show fields for "Academic Guest"
            fetchUniversities();        // Fetch universities for Academic Guest selection
        } else if (bookingType === 'non_academic') {
            $("#nonAcademicGuestFields").show(); // Show fields for "Non-Academic Guest"
        }
    }

    // Show or hide university and department fields for "Academic Guest"
    $("#guest_type").change(function () {
        const guestType = $(this).val();

        if (guestType === "academic") {
            $("#academicGuestFields").show();  // Show university and department fields for academic guest
            $("#nonAcademicGuestFields").hide(); // Hide non-academic fields
            fetchUniversities(); // Fetch universities for "Academic Guest" type
        } else if (guestType === "non_academic") {
            $("#academicGuestFields").hide();  // Hide academic fields
            $("#nonAcademicGuestFields").show(); // Show non-academic fields
        }
    });

    // Fetch available universities when "Academic Guest" type is selected
    function fetchUniversities() {
        $.ajax({
            url: "fetch_universities.php", // Fetch universities from the database
            type: "GET",
            success: function(response) {
                const universities = JSON.parse(response);
                $("#guest_university").html('<option value="">Select University</option>');
                universities.forEach(function(university) {
                    $("#guest_university").append('<option value="' + university.id + '">' + university.name + '</option>');
                });
            },
            error: function() {
                alert("Error loading universities!");
            }
        });
    }

    // Fetch departments based on selected university for Academic Guest
    $("#guest_university").change(function () {
        const university_id = $(this).val();
        if (university_id) {
            $.ajax({
                url: "fetch_departments.php", // Fetch departments based on university
                type: "POST",
                data: { university_id: university_id },
                success: function(response) {
                    const departments = JSON.parse(response);
                    $("#department_academic").html('<option value="">Select Department</option>');
                    departments.forEach(function(department) {
                        $("#department_academic").append('<option value="' + department.id + '">' + department.name + '</option>');
                    });
                },
                error: function() {
                    alert("Error loading departments!");
                }
            });
        }
    });

    // Handle booking form submission
    $("#bookingForm").submit(function(event) {
        event.preventDefault();

        const verification_code = $("#verification_code").val(); // Get the verification code
        const checkin = $("#checkin").val();
        const checkout = $("#checkout").val();
        const room_type = $("#room_type").val();
        const rooms = $("#rooms").val(); // Get selected rooms

        // Calculate the number of nights between check-in and check-out
        const checkinDate = new Date(checkin);
        const checkoutDate = new Date(checkout);
        const diffTime = checkoutDate - checkinDate;
        const days = diffTime / (1000 * 3600 * 24); // Calculate days

        if (days <= 0) {
            $("#message").html("Invalid date range!").css("color", "red");
            return;
        }

        // Verify the verification code before proceeding
        $.ajax({
            url: "verify_code.php", // Send the code to verify
            type: "POST",
            data: {
                verification_code: verification_code
            },
            success: function(response) {
                if (response === "valid") {
                    // If verification code is valid, proceed with the booking
                    $.post("ssubmit_booking.php", {
                        checkin: checkin,
                        checkout: checkout,
                        room_type: room_type,
                        room: rooms,
                        days: days,
                        user_id: $("#user_id").val(),
                        role: $("#role").val()
                    }, function (response) {
                        // Handle booking success
                        $("#message").html(response).css("color", "green");
                        $("#bookingForm")[0].reset();
                        $("#rooms").html('<option value="">Select Check-in and Check-out Date</option>');
                    }).fail(function () {
                        $("#message").html("Booking failed!").css("color", "red");
                    });
                } else {
                    $("#message").html("Invalid verification code!").css("color", "red");
                }
            },
            error: function () {
                $("#message").html("Error verifying code!").css("color", "red");
            }
        });
    });
});
</script>

</body>
</html>
