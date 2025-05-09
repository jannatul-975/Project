<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create New Profile</title>
    <link rel="stylesheet" href="formStyle.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function () {
        // Submit form using AJAX
        $("#profileForm").submit(function (event) {
          event.preventDefault(); // Prevent default form submission

          let formData = $(this).serialize();

          $.ajax({
            url: "Form.php", // PHP file to handle form submission
            type: "POST",
            data: formData,
            success: function (response) {
              $("#message").html(response).css("color", "green");
              $("#profileForm")[0].reset(); // Reset form after submission
            },
            error: function () {
              $("#message").html("An error occurred!").css("color", "red");
            },
          });
        });
      });
    </script>
    <?php
    include('../db.php'); // Include your database connection file
    ?>
</head>
<body>
    <div class="container">
        <h3>Create New Profile</h3>
        <form method="POST" action="../php/Form.php" id="profileForm">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />

            <label for="phone">Phone No:</label>
            <input type="text" id="phone" name="phone" required />

            <label for="designation">Designation:</label>
            <select id="designation" name="designation" required>
                <option value="Professor">Professor</option>
                <option value="Associate Professor">Associate Professor</option>
                <option value="Assistant Professor">Assistant Professor</option>
                <option value="Lecturer">Lecturer</option>
            </select>

            <label for="dept_name">Discipline:</label>
            <select id="dept_name" name="dept_name" required>
                <?php
                // Fetch departments from the database
                $sql = "SELECT name FROM department WHERE university_id=1 ORDER BY name";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value='-1'>No Discipline</option>";
                }
                ?>
            </select>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Teacher">Teacher</option>
                <option value="Officestaff">Officestaff</option>
                <option value="Register">Register</option>
                <option value="Vice Chancellor">Vice Chancellor</option>
                <option value="Pro-Vice Chancellor">Pro-Vice Chancellor</option>
                <option value="Treasurer">Treasurer</option>
            </select>

            <button type="submit" id="submitButton">Submit</button>
        </form>
        <p id="message"></p>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
