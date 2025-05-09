<?php
include '../../../db.php';
include('../dashboard/a_header.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment History</title>
    <style>
        #header{
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        table {
            border-collapse: separate;
            border-spacing: 2px;
            border-radius: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        th {
            background-color: #1976D2; /* Primary Blue */
            color: white;
            text-align: left;
            padding: 12px;
            font-weight: bold;
        }

        td {
            padding: 10px 12px;
            background-color: #e3f2fd;
            border-top: 1px solid #e0e0e0;
        }
        .status_search{
            width: 400px;
            margin-right: 0px;
            margin-left:650px;
            max-width: 450px;
            margin: 10px auto 30px auto;
            text-align: right;
            margin-right: 0px;

        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <div id="header">
        <h4>Make Payment</h4>
    </div>
    
    <div class="status_search">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by Check Out Date,phone,Discipline"/>
        
    </div>
    
    <br>
    <div id="paymentTable">
        <table>
            <thead>
                <tr>
                    <th>Name & Address</th>
                    <th>Room(s)</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Paid Amount</th>
                    <th>Travel Purpose</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Initial rows will be filled by AJAX -->
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function () {
    function loadTable(query = '') {
        $.ajax({
            url: 'payment_entry',
            method: 'GET',
            dataType: 'json',
            data: { q: query },
            success: function (response) {
            $('#paymentTable tbody').html(response.html);
            }
        });
    }

    // Load all data on page load
    loadTable();

    // Search as user types
    $('#searchInput').on('input', function () {
        let searchTerm = $(this).val();
        loadTable(searchTerm);
    });


});
</script>

</body>
</html>
