<?php
include '../../../db.php';
include('../dashboard/a_header.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking History</title>
    <style>
        #header{
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        table {
            width: 1050px;
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
        .search{
            display: flex;
            justify-content: space-between;
            gap: 5px;
            width:200px;
            margin-left: 0px;
            margin-top: 5px;
        }
        button{
            background-color: #117554;
        }
        .btn-download:hover{
            background-color:rgb(15, 95, 68);
        }
        .btn-filter:hover{
            background-color:rgb(15, 95, 68);
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <div id="header">
        <h4>Guest Booking Information</h4>
    </div>
    
    <div class="status_search">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by Discipline, Guest Phone, or Check-In/Out Date" />
    </div>

    <div class="search">
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" class="form-control" />
    
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" class="form-control" />

        <button id="filterBtn" class="btn-filter">Filter</button>
        <button id="downloadBtn" class="btn-download">Download</button>
    </div>

</div>


    <br>
    <div id="guestTable">
        <table>
            <thead>
                <tr>
                    <th>Teacher/Guest</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Rooms</th>
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
            url: 'search_booking.php',
            method: 'GET',
            data: { q: query },
            success: function (response) {
                $('#guestTable tbody').html(response);
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
    // Filter when button is clicked
    $('#filterBtn').on('click', function () {
        loadTable($('#searchInput').val(), $('#startDate').val(), $('#endDate').val());
    });

    //For download
    document.getElementById("downloadBtn").addEventListener("click", function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Title
        doc.setFontSize(16);
        doc.text("Guest Booking Information", 14, 15);

        // Generate PDF from HTML table
        doc.autoTable({
            html: "table", // selector for your table
            startY: 25,
            theme: 'striped',
            headStyles: { fillColor: [25, 118, 210] }, // match your header blue
        });

        doc.save("guest_booking_information.pdf");
    });
});
</script>

</body>
</html>
