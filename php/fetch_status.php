<?php
        include('db.php');
        if (session_status() === PHP_SESSION_NONE) {
        session_start();
        }

        // Get the current logged-in teacher's ID
        $teacher_id = $_SESSION['user_id'] ?? 0;

        // If the user is not logged in or teacher_id is missing, return an error message
        if (!$teacher_id) {
            echo "Unauthorized access!";
            exit;
        }

        // Get the filter and search values from POST request
        $statusFilter = $_POST['status'] ?? '';  // Filter by status (Approved, Pending, Rejected)
        $search = $_POST['search'] ?? '';        // Search query for Guest Name or Date

        // Base query to fetch applications (no JOIN with user table, using purpose instead of teacher name)
        $query = "
            SELECT 
                ta.applicationId, 
                ta.userId, 
                ta.guestInformation, 
                ta.checkInDate, 
                ta.checkOutDate, 
                ta.status, 
                ta.submission_date, 
                ta.purpose,  
                ta.verification_code
            FROM application ta
            WHERE ta.userId = ?";  // Only fetch applications for the logged-in teacher

        // Apply filters
        if ($statusFilter) {
            $query .= " AND ta.status = ?";  // Add status filter
        }

        if ($search) {
            $query .= " AND (ta.guestInformation LIKE ? OR ta.checkInDate LIKE ? OR ta.checkOutDate LIKE ?)";  // Add search filters
        }

        // Prepare the statement
        $stmt = $conn->prepare($query);

        // Bind parameters based on conditions
        if ($statusFilter && $search) {
            $searchParam = "%$search%";
            $stmt->bind_param("sssss", $teacher_id, $statusFilter, $searchParam, $searchParam, $searchParam);
        } elseif ($statusFilter) {
            $stmt->bind_param("s", $teacher_id, $statusFilter);  // Bind only teacher_id and status
        } elseif ($search) {
            $searchParam = "%$search%";
            $stmt->bind_param("ssss", $teacher_id, $searchParam, $searchParam, $searchParam);  // Bind only teacher_id and search
        } else {
            $stmt->bind_param("s", $teacher_id);  // Only bind teacher_id
        }

        // Execute the query
        $stmt->execute();

        // Fetch the results
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            echo '<table class="table">';
            echo '<thead><tr><th>Purpose</th><th>Guest Information</th><th>Check-in Date</th><th>Check-out Date</th><th>Status</th><th>Submission Date</th><th>Verification Code</th></tr></thead><tbody>';

            // Output the results in a table
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['purpose'] . '</td>';
                echo '<td>' . $row['guestInformation'] . '</td>';
                echo '<td>' . $row['checkInDate'] . '</td>';
                echo '<td>' . $row['checkOutDate'] . '</td>';
                echo '<td>' . $row['status'] . '</td>';
                echo '<td>' . $row['submission_date'] . '</td>';
                echo '<td>' . $row['verification_code'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo "<div class='alert alert-info'>No applications found.</div>";
        }

        ?>