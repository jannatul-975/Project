$(document).ready(function () {
  // Set the default selection for "Myself" and show its fields
  const bookingType = $("#booking_type").val(); // Get the default selected value of booking type
  toggleBookingFields(bookingType); // Call function to display relevant fields based on the default booking type

  // Toggle fields based on Booking Type selection
  $("#booking_type").change(function () {
    const bookingType = $(this).val();
    toggleBookingFields(bookingType); // Update fields based on the selected booking type
  });

  // Function to show/hide relevant fields based on the selected booking type
  function toggleBookingFields(bookingType) {
    // Hide all fields first
    $("#myselfFields, #academicGuestFields, #nonAcademicGuestFields").hide();

    // Show fields based on selected booking type
    if (bookingType === "myself") {
      $("#myselfFields").show(); // Show fields for "Myself"
      loadKhulnaUniversityDepartments(); // Load departments for Khulna University when "Myself" is selected
    } else if (bookingType === "academic") {
      $("#academicGuestFields").show(); // Show fields for "Academic Guest"
      fetchUniversities(); // Fetch universities for Academic Guest selection
    } else if (bookingType === "non_academic") {
      $("#nonAcademicGuestFields").show(); // Show fields for "Non-Academic Guest"
    }
  }

  // Load Khulna University departments when "Myself" is selected
  function loadKhulnaUniversityDepartments() {
    // Set Khulna University as the only option for the university dropdown
    $("#guest_university").html(
      '<option value="1">Khulna University (Khulna, Bangladesh)</option>'
    );

    // Fetch and load departments for Khulna University (university_id = 1)
    $.ajax({
      url: "fetch_departments_for_myself.php", // PHP file to fetch Khulna University departments
      type: "GET",
      success: function (response) {
        const departments = JSON.parse(response);

        // Clear any existing department options
        $("#department_myself").html(
          '<option value="">Select Department</option>'
        );

        // Add department options for Khulna University
        departments.forEach(function (department) {
          $("#department_myself").append(
            '<option value="' +
              department.id +
              '">' +
              department.name +
              "</option>"
          );
        });
      },
      error: function () {
        alert("Error loading departments for Khulna University!");
      },
    });
  }

  // Fetch universities dynamically for "Academic Guest"
  function fetchUniversities() {
    $.ajax({
      url: "fetch_universities.php", // Fetch universities from the database
      type: "GET",
      success: function (response) {
        const universities = JSON.parse(response);
        $("#guest_university").html(
          '<option value="">Select University</option>'
        ); // Reset the dropdown
        universities.forEach(function (university) {
          $("#guest_university").append(
            '<option value="' +
              university.id +
              '">' +
              university.name +
              "</option>"
          );
        });
      },
      error: function () {
        alert("Error loading universities!");
      },
    });
  }

  // Fetch departments dynamically based on the selected university
  $("#guest_university").change(function () {
    const university_id = $(this).val(); // Get selected university ID
    if (university_id) {
      $.ajax({
        url: "fetch_departments.php", // Fetch departments based on university
        type: "POST",
        data: { university_id: university_id },
        success: function (response) {
          const departments = JSON.parse(response);
          $("#department_academic").html(
            '<option value="">Select Department</option>'
          ); // Reset the department dropdown
          departments.forEach(function (department) {
            $("#department_academic").append(
              '<option value="' +
                department.id +
                '">' +
                department.name +
                "</option>"
            );
          });
        },
        error: function () {
          alert("Error loading departments!");
        },
      });
    } else {
      // If no university is selected, clear the department dropdown
      $("#department_academic").html(
        '<option value="">Select Department</option>'
      );
    }
  });

  // Fetch available rooms when check-in and check-out dates are selected
  $("#checkin, #checkout").change(function () {
    const checkin = $("#checkin").val();
    const checkout = $("#checkout").val();

    // Ensure both dates are selected
    if (checkin && checkout) {
      fetchAvailableRooms(checkin, checkout); // Fetch available rooms based on selected dates
    }
  });

  // Fetch available rooms based on selected check-in and check-out dates
  function fetchAvailableRooms(checkin, checkout) {
    const room_type = $("#room_type").val(); // Get selected room type

    $.ajax({
      url: "get_available_rooms.php", // PHP file to fetch available rooms
      type: "POST",
      data: {
        checkin: checkin,
        checkout: checkout,
        room_type: room_type,
      },
      success: function (response) {
        // Clear any existing room options
        $("#rooms").html(""); // Clear the dropdown

        // If rooms are available, populate them
        if (response.trim() !== "") {
          const rooms = JSON.parse(response);

          // Add available room options to the dropdown
          rooms.forEach(function (room) {
            $("#rooms").append(
              '<option value="' +
                room.id +
                '">' +
                room.name +
                " - " +
                room.room_type +
                " (" +
                room.pricePerNight +
                " BDT/night)</option>"
            );
          });
        } else {
          // If no rooms are available, we don't add anything, or we can display a message
          $("#rooms").html(""); // Keep the dropdown empty if no rooms are available
        }
      },
      error: function () {
        $("#rooms").html(""); // Handle the error by clearing the dropdown
      },
    });
  }

  // Filter rooms based on selected room type
  $("#room_type").change(function () {
    const checkin = $("#checkin").val();
    const checkout = $("#checkout").val();

    // Ensure both dates are selected
    if (checkin && checkout) {
      fetchAvailableRooms(checkin, checkout); // Filter available rooms based on room type
    }
  });

  // Handle booking form submission
  $("#bookingForm").submit(function (event) {
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
        verification_code: verification_code,
      },
      success: function (response) {
        if (response === "valid") {
          // If verification code is valid, proceed with the booking
          $.post(
            "ssubmit_booking.php",
            {
              checkin: checkin,
              checkout: checkout,
              room_type: room_type,
              room: rooms,
              days: days,
              user_id: $("#user_id").val(),
              role: $("#role").val(),
            },
            function (response) {
              // Handle booking success
              $("#message").html(response).css("color", "green");
              $("#bookingForm")[0].reset();
              $("#rooms").html(
                '<option value="">Select Check-in and Check-out Date</option>'
              );
            }
          ).fail(function () {
            $("#message").html("Booking failed!").css("color", "red");
          });
        } else {
          $("#message").html("Invalid verification code!").css("color", "red");
        }
      },
      error: function () {
        $("#message").html("Error verifying code!").css("color", "red");
      },
    });
  });
});
