<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();  // Ensure session is started
include('db.php');  // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Content-Type: application/json");  // Set the content type to JSON

    // Get the email and password from POST request
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Log the incoming POST data
    error_log("Received POST data: " . print_r($_POST, true)); // Log POST data

    // First, check if the user exists in the `user` table
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // User found in `user` table
        if (password_verify($password, $user['password'])) {
            // Regenerate session to prevent session fixation
            session_regenerate_id(true);

            // Store session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = strtolower($user['role']);
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['profile_picture'] = $user['profile_pic'];

            // Log session data
            error_log("Session data set: " . print_r($_SESSION, true));

            // Define redirection based on role
            $role = $_SESSION['role'];
            if ($role === "teacher") {
                $redirect = "../php/teacher.php";
            } elseif ($role === "officestaff") {
                $redirect = "../php/officeStaff.php";
            } elseif ($role === "register") {
                $redirect = "../php/register.php";
            } else {
                $redirect = "../php/administrator.php"; // Default for admin and others
            }

            // Log the redirect URL
            error_log("Redirecting to: " . $redirect);

            echo json_encode([
                "status" => "success",
                "redirect" => $redirect
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Incorrect password."
            ]);
        }
    } else {
        // Check the `attendant` table if no user found in the `user` table
        $stmt = $conn->prepare("SELECT * FROM attendant WHERE email = ? AND is_active = 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($attendant = $result->fetch_assoc()) {
            // Attendant found and is active
            if (password_verify($password, $attendant['password'])) {
                // Regenerate session to prevent session fixation
                session_regenerate_id(true);

                // Store session data
                $_SESSION['user_id'] = $attendant['id'];
                $_SESSION['role'] = "attendant";
                $_SESSION['email'] = $attendant['email'];
                $_SESSION['name'] = $attendant['name'];
                $_SESSION['profile_picture'] = $attendant['profile_pic'];

                // Log session data
                error_log("Session data set for attendant: " . print_r($_SESSION, true));

                // Redirect to attendant's profile
                echo json_encode([
                    "status" => "success",
                    "redirect" => "../php/attendant.php"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Incorrect password."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Email not found or inactive."
            ]);
        }
    }

    $stmt->close();
    $conn->close();
    exit;  // Exit to prevent any further output after sending JSON response
}
?>



<!-- HTML Structure -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Khulna University Guest House</title>
    <link rel="stylesheet" href="../css/login.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <!-- Header Section -->
    <?php include('header.php'); ?>

    <!-- Form Section -->
    <div class="form-container" autocomplete="on">
      <h2>Login</h2>
      <form id="loginForm">
        <input type="email" name="email" placeholder="Email" required autocomplete="email" />
        <input type="password" name="password" placeholder="Password" required autocomplete="current-password"/>
        <div class="button-group">
          <button type="submit">Login</button>
        </div>
      </form>
      <div id="loginMessage"></div>
    </div>

    <!-- Footer Section -->
    <?php include('footer_index.php'); ?>

    <script src="../js/login.js"></script>
    <script src="../js/script.js"></script>
  </body>
</html>
