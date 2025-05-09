<?php
include '../../../db.php';

$search = $_GET['q'] ?? '';
$param = '%' . $search . '%';

// Base query
$query = "
SELECT 
    u.name AS user_name,
    u.designation,
    u.dept_name,
    u.phone AS Phone,
    GROUP_CONCAT(r.RoomName SEPARATOR ', ') AS roomname,
    b.checkInDate,
    b.checkOutDate,
    b.totalAmount AS amount,
    b.travelPurpose,
    g.name AS gname,
    g.phone AS gphone,
    g.address,
    d.name as deptName,
    uni.name as uniName,
    b.paymentStatus,
    b.bookingId,
    CASE
        WHEN b.guestId is NULL THEN 'Myself'
        ELSE g.guestType
    END AS guest
FROM booking b
LEFT JOIN application ta ON b.applicationId = ta.applicationId
LEFT JOIN user u ON u.id = COALESCE(b.userId, ta.userId)
JOIN booking_room br ON br.bookingId = b.bookingId
JOIN room r ON r.RoomID = br.RoomID
LEFT JOIN guest g ON b.guestId = g.guestId
LEFT JOIN department d ON g.dept = d.department_id
LEFT JOIN university uni ON d.university_id = uni.university_id
WHERE (
    u.dept_name LIKE ? 
    OR u.phone LIKE ? 
    OR g.phone LIKE ?
    OR b.checkOutDate LIKE ?
)
GROUP BY b.bookingId 
ORDER BY b.checkOutDate DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $param, $param, $param, $param);
$stmt->execute();
$result = $stmt->get_result();

$html = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nameAddressCell = "";

        // Format name/address by guest type
        if ($row['guest'] == 'Myself') {
            $nameAddressCell = "Name: {$row['user_name']}<br>Phone: {$row['Phone']}<br>Designation: {$row['designation']}<br>Discipline: {$row['dept_name']}";
        } elseif ($row['guest'] == 'Non Academic') {
            $nameAddressCell = "Name: {$row['gname']}<br>Phone: {$row['gphone']}<br>Address: {$row['address']}";
        } elseif ($row['guest'] == 'Academic') {
            $nameAddressCell = "Name: {$row['gname']}<br>Phone: {$row['gphone']}<br>Department: {$row['deptName']}<br>University: {$row['uniName']}";
        }

        // Start row
        $html .= "<tr>
            <td>$nameAddressCell</td>
            <td>{$row['roomname']}</td>
            <td>{$row['checkInDate']}</td>
            <td>{$row['checkOutDate']}</td>
            <td>{$row['amount']}</td>
            <td>{$row['travelPurpose']}</td>";
        
        // Payment status
        if ($row['paymentStatus'] === 'Pending') {
            $html .= "<td><a href='payment.php?id={$row['bookingId']}' class='paymentButton'>Add Payment</a></td>";
        } elseif ($row['paymentStatus'] === 'Paid') {
            $html .= "<td>Paid</td>";
        } else {
            $html .= "<td></td>";
        }

        // Close row
        $html .= "</tr>";
    }

    echo json_encode([
        'html' => $html,
    ]);
} else {
    echo json_encode([
        'html' => "<tr><td colspan='9'>No matching records found.</td></tr>",
    ]);
}
?>
