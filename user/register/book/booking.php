<!DOCTYPE html>
<html lang="en">
<head>
    <title>Room Booking Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../book/bookingStyle.css" />
    <link rel="stylesheet" href="../../../dashboard.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include('../dashboard/r_header.php'); ?> 
<!-- Main Content for the Booking Form -->
<div class="container">
    <h2>Room Booking Form</h2>

    <form id="bookingForm">
        <!-- Booking Type -->
        <label for="booking_type">Booking Type</label>
        <select id="booking_type" name="booking_type" class="form-control" required>
            <option value="myself">Myself</option>
            <option value="academic">Academic Guest</option>
            <option value="non_academic">Non-Academic Guest</option>
        </select>

        <!-- Academic Guest Fields -->
        <div id="academicGuestFields" style="display: none;">
            <label for="g_hone">Phone No</label>
            <input type="text" id="guest_phone" name="guest_phone" class="form-control" placeholder="Enter Guest's Phone Number" required />
            <label for="guest_university">University Name</label>
            <select id="guest_university" name="guest_university" class="form-control" required>
            </select>

            <label for="department_academic">Department Name</label>
            <select id="department_academic" name="department" class="form-control" required>
                <option value="">Select an University</option>
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
        <br>
        <label for="room_type">Room Type:</label>
        <select id="room_type" name="room_type">
            <option value="">All</option>
            <option value="VIP">VIP</option>
            <option value="AC">AC</option>
            <option value="Non AC double">Non AC double</option>
            <option value="Non AC single">Non AC single</option>
        </select>

        <label for="room">Available Rooms:</label>
        <select name="room[]" id="rooms" multiple required>
            <option value="">Select room</option>
        </select>
        <br>
        <label for="travelPurpose">Travel Purpose</label>
        <input type="text" name="travelPurpose" id="travelPurpose" required />
        
        <button type="submit" id="submitBooking">Submit Booking</button>
    </form>

    <p id="message"></p>
</div>

<script>
$(document).ready(function () {
    const bookingType = $("#booking_type").val();  // Get the default selected value of booking type
    toggleBookingFields(bookingType);  // Call function to display relevant fields based on the default booking type

    // Disable previous dates for the booking calendar
    const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
    $('#checkin, #checkout').attr('min', today); // Set the 'min' attribute for check-in and check-out dates

    // Toggle fields based on Booking Type selection
    $("#booking_type").change(function () {
        const bookingType = $(this).val();
        toggleBookingFields(bookingType);  // Update fields based on the selected booking type
    });

    function toggleBookingFields(bookingType) {
        // Hide both sections initially
        $("#academicGuestFields, #nonAcademicGuestFields").hide();

        // Disable all inputs/selects in both sections so they're ignored by browser validation
        $("#academicGuestFields input, #academicGuestFields select").prop("disabled", true);
        $("#nonAcademicGuestFields input, #nonAcademicGuestFields textarea").prop("disabled", true);

        if (bookingType === 'academic') {
            $("#academicGuestFields").show(); // Show academic section
            $("#academicGuestFields input, #academicGuestFields select").prop("disabled", false); // Enable inputs
            fetchUniversities(); // Fetch university list
        } else if (bookingType === 'non_academic') {
            $("#nonAcademicGuestFields").show(); // Show non-academic section
            $("#nonAcademicGuestFields input, #nonAcademicGuestFields textarea").prop("disabled", false); // Enable inputs
        }
    }


    function fetchUniversities() {
        $.ajax({
            url: "../book/fetch_universities.php",
            type: "GET",
            success: function(response) {
                const universities = JSON.parse(response);
                $("#guest_university").html('<option value="">Select University</option>');
                universities.forEach(function(university) {
                    $("#guest_university").append('<option value="' + university.university_id + '">' + university.name + '</option>');
                });
            },
            error: function() {
                alert("Error loading universities!");
            }
        });
    }

    $("#guest_university").change(function () {
        const university_id = $(this).val();
        if (university_id) {
            $.ajax({
                url: "../book/fetch_departments.php",
                type: "POST",
                data: { university_id: university_id },
                success: function(response) {
                    const departments = JSON.parse(response);
                    $("#department_academic").html('<option value="">Select Department</option>');
                    departments.forEach(function(department) {
                        $("#department_academic").append('<option value="' + department.department_id + '">' + department.name + '</option>');
                    });
                },
                error: function() {
                    alert("Error loading departments!");
                }
            });
        }
    });

    // Trigger the AJAX request when the room type is changed
    $("#room_type").change(function () {
        const roomType = $(this).val();

        // Make AJAX call to fetch available rooms based on the selected room type
        $.ajax({
            url: '../book/fetch_rooms.php',
            type: 'GET',
            data: { room_type: roomType },
            success: function(response) {
                const rooms = JSON.parse(response);
                $("#rooms").html(''); // Clear the rooms dropdown
                if (rooms.length > 0) {
                    rooms.forEach(function(room) {
                        $("#rooms").append('<option value="' + room.RoomID + '">' + room.RoomName +" ("+room.pricePerNight +" TK)"+ '</option>');
                    });
                } else {
                    $("#rooms").append('<option value="">No available rooms</option>');
                }
            },
            error: function() {
                alert("Error loading available rooms!");
            }
        });
    });

    // Submit the form via AJAX
    $('#bookingForm').submit(function (event) {
        event.preventDefault();  // Prevent default form submission

        // const formData = $(this).serialize(); // Serialize the form data

        // Send data via AJAX to submit_booking.php
        $.ajax({
            url:'../book/submit_booking.php',
            type: 'POST',
            data: $(this).serialize(),  // Serialize the form data
            dataType: "json",
            success: function (response) {
                // response= JSON.parse(response);  // Parse the JSON response
                console.log(response);  // Log the response for debugging
                console.log("form submitted");
                // Handle response from submit_booking.php
                if (response.status === 'success') {
                    $('#message').text('Booking submitted successfully!');  // Display success message
                } else {
                    $('#message').text('Error: ' + response.message);  // Display error message
                    console.log("undefined error");
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error: " + xhr.status + " " + status + " " + error);
                $('#message').text('An error occurred. Please try again.');  // Display AJAX error message
        }

        });
    });

});

</script>

</body>
</html>
