<?php include('../../../db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Applications</title>
    <!-- <link rel="stylesheet" href="../css/dashboard.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include('../dashboard/r_header.php');?>
<div class="container">
    <h1>Applications</h1>

    <div class="status">
        <div class="status_search">
            <form method="POST">
                <select id="statusFilter" name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Approved" <?= isset($_POST['status']) && $_POST['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="Pending" <?= isset($_POST['status']) && $_POST['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Rejected" <?= isset($_POST['status']) && $_POST['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </form>
        </div>
        <div class="status_search">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Name,Discipline, or Submission Date" />
        </div>
    </div>

   <div id="statusTable">
        <!-- This is where the search results will be inserted -->
    </div>
</div>

<script>
    // Listen to the search input and send the request when the user types
    $('#searchInput').on('keyup', function() {
        var search = $(this).val();
        var status = $('#statusFilter').val(); // Get the selected status
        
        // Send an AJAX request to get the filtered data
        $.ajax({
            url: '../application/search_results.php',
            type: 'GET',
            data: {
                search: search,
                status: status
            },
            success: function(data) {
                $('#statusTable').html(data); // Replace the table with the new results
            }
        });
    });

    // Trigger search when the page loads with the default values
    $(document).ready(function() {
        $('#searchInput').trigger('keyup');
    });
</script>

</body>
</html>
