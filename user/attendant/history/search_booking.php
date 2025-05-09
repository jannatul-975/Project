
<?php
include '../../../db.php';

$search = $_GET['q'] ?? '';
$startDate = $_GET['start'] ?? '';
$endDate = $_GET['end'] ?? '';

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
    b.travelPurpose,
    g.name AS gname,
    g.phone AS gphone,
    g.address,
    d.name as deptName,
    uni.name as uniName,
    CASE
        WHEN b.guestId IS NULL THEN 'Myself'
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
";


// Conditionally append date filtering
$params = [$param, $param, $param, $param];
$types = "ssss";

if (!empty($startDate) && !empty($endDate)) {
    $query .= " AND b.checkInDate BETWEEN ? AND ?";
    $params[] = $startDate;
    $params[] = $endDate;
    $types .= "ss";
}

$query .= " GROUP BY b.bookingId ORDER BY b.checkInDate DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
echo ($result->num_rows);
$html = "";
if ($result->num_rows > 0) {
 
    while ($row = $result->fetch_assoc()) {
        $nameAddressCell = "";

        // Check guest type and format the name/address accordingly
        if ($row['guest'] == 'Myself') {
            $nameAddressCell = "Name: {$row['user_name']}<br>Phone:{$row['Phone']}<br>Designation: {$row['designation']}<br>Discipline: {$row['dept_name']}";
        } elseif ($row['guest'] == 'Non Academic') {
            $nameAddressCell = "Name: {$row['gname']}<br>Phone: {$row['gphone']}<br>Address: {$row['address']}";
        } elseif ($row['guest'] == 'Academic') {
            $nameAddressCell = "Name: {$row['gname']}<br>Phone: {$row['gphone']}<br>Department: {$row['deptName']}<br>University: {$row['uniName']}";
        }

        $html .= "<tr>
            <td>$nameAddressCell</td>
            <td>{$row['checkInDate']}</td>
            <td>{$row['checkOutDate']}</td>
            <td>{$row['roomname']}</td>
        </tr>";
    }
    echo $html;
    
} else {
    echo "<tr><td colspan='9'>No matching records found.</td></tr>";
}
?>
