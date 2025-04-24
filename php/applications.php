<?php
// Start the session
session_start();
include('dbconnect.php');

// Check if the user is logged in
$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['id'] ?? 0;

// If the role or user_id is not set, redirect to login
if (!$role || !$user_id) {
    echo "Unauthorized access!";
    header('Location: login.php');
    exit;
}

// Fetch active register info (if needed)
$query = "SELECT * FROM register WHERE is_active = 1 LIMIT 1";
$result = $conn->query($query);
$register = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="teacher.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include('r_header.php'); ?>

    <div class="container">
        <h3 class="mb-4">Applications</h3>

        <!-- Filters -->
        <form id="filters-form">
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select">
                        <option value="">All</option>
                        <option value="Approved">Approved</option>
                        <option value="Pending">Pending</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by Guest, Teacher, Department or Date">
                </div>
            </div>
        </form>

        <!-- Applications Table -->
        <div id="applications-table">
            <!-- Table data will be loaded here -->
        </div>
    </div>

    <script>
    function fetchApplications() {
        const status = $('#statusFilter').val();
        const search = $('#searchInput').val();

        $.ajax({
            url: 'fetch_applications.php',
            method: 'POST',
            data: { status: status, search: search },
            success: function (response) {
                $('#applications-table').html(response);
            }
        });
    }

    $(document).ready(function () {
        fetchApplications();
        $('#statusFilter, #searchInput').on('change keyup', fetchApplications);
    });

    // Handle Approve and Reject buttons
    $(document).on('click', '.approve-btn', function () {
        const applicationID = $(this).data('id');
        
        if (confirm('Are you sure you want to approve this application?')) {
            $.ajax({
                url: 'process_application.php',
                method: 'POST',
                data: { action: 'approve', applicationID: applicationID },
                success: function(response) {
                    alert(response);
                    fetchApplications();  // Refresh the applications list
                }
            });
        }
    });

    $(document).on('click', '.reject-btn', function () {
        const applicationID = $(this).data('id');
        
        if (confirm('Are you sure you want to reject this application?')) {
            $.ajax({
                url: 'process_application.php',
                method: 'POST',
                data: { action: 'reject', applicationID: applicationID },
                success: function(response) {
                    alert(response);
                    fetchApplications();  // Refresh the applications list
                }
            });
        }
    });
    </script>
</body>
</html>
