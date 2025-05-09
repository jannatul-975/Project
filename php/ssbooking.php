<!DOCTYPE html>
<html lang="en">
<head>
    <title>Room Booking Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/bookingStyle.css" />
    <link rel="stylesheet" href="../css/dashboard.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include('r_header.php'); ?> 

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

        <!-- Academic Guest Fields -->
        <div id="academicGuestFields" style="display: none;">
            <label for="guest_phone">Phone No</label>
            <input type="text" id="guest_phone" name="guest_phone" class="form-control" placeholder="Enter Guest's Phone Number" required />
            <label for="guest_university">University Name</label>
            <select id="guest_university" name="guest_university" class="form-control" required>
                <option value="">Select University</option>
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
        <input type="number" name="days" id="days" required />

        <button type="submit">Submit Booking</button>
    </form>

    <p id="message"></p>
</div>

<script>
$(document).ready(function () {
    const bookingType = $("#booking_type").val();  // Get the default selected value of booking type
    toggleBookingFields(bookingType);  // Call function to display relevant fields based on the default booking type

    // Toggle fields based on Booking Type selection
    $("#booking_type").change(function () {
        const bookingType = $(this).val();
        toggleBookingFields(bookingType);  // Update fields based on the selected booking type
    });

    function toggleBookingFields(bookingType) {
        // Hide all fields first
        $("#academicGuestFields, #nonAcademicGuestFields").hide();

       if (bookingType === 'academic') {
            $("#academicGuestFields").show();
            fetchUniversities();
        } else if (bookingType === 'non_academic') {
            $("#nonAcademicGuestFields").show();
        }
    }

    function fetchUniversities() {
        $.ajax({
            url: "../php/fetch_universities.php",
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
                url: "../php/fetch_departments.php",
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

    $("#checkin, #checkout").change(function () {
        const checkin = $("#checkin").val();
        const checkout = $("#checkout").val();

        if (checkin && checkout) {
            $.ajax({
                url: "../php/get_available_rooms.php",
                type: "POST",
                data: { checkin: checkin, checkout: checkout },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.error) {
                        $("#rooms").html('<option value="">Error: ' + data.error + '</option>');
                    } else if (data.message) {
                        $("#rooms").html('<option value="">Message: ' + data.message + '</option>');
                    } else {
                        $("#rooms").html('<option value="">Select Room</option>');
                        data.forEach(function(room) {
                            $("#rooms").append('<option value="' + room.id + '">' + room.name + '</option>');
                        });
                    }
                },
                error: function() {
                    $("#rooms").html('<option value="">Error loading rooms!</option>');
                }
            });
        }
    });

    var today = new Date().toISOString().split('T')[0];
    $("#checkin, #checkout").attr("min", today);

    $("#bookingForm").submit(function(event) {
        event.preventDefault();
    });
});
</script>

</body>
</html>
