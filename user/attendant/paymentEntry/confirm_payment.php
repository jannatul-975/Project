<?php
include '../../../db.php';

$bookingId = (int) ($_POST['bookingId'] ?? 0);
$attendantId = (int) ($_POST['attendantId'] ?? 0);
$paidAmount = (float) ($_POST['paid_amount'] ?? 0);
$totalAmount = (float) ($_POST['totalAmount'] ?? 0);
$name = $_POST['name'] ?? 'Guest';
$phone = $_POST['phone'] ?? 'N/A';

if ($paidAmount >= $totalAmount) {
    $stmt = $conn->prepare("UPDATE booking SET paymentStatus = 'Paid', attendantId = ? WHERE bookingId = ?");
    $stmt->bind_param("ii", $attendantId, $bookingId);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'bookingId' => $bookingId,
            'name' => $name,
            'phone' => $phone,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'returnAmount' => $paidAmount - $totalAmount,
            'date' => date('d-m-Y H:i')
        ]);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Paid amount is less than required.']);
    exit;
}
?>
