<?php
include '../../../db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$attendantId = $_SESSION['user_id'] ?? null;
if (!$attendantId) {
    die("Access denied.");
}

$bookingId = $_GET['id'] ?? null;
if (!$bookingId) {
    die("No booking selected.");
}

$stmt = $conn->prepare("
    SELECT b.bookingId, b.totalAmount, b.guestId, b.paymentStatus,
           COALESCE(g.phone, u.phone) AS phone,
           COALESCE(g.name, u.name) AS name
    FROM booking b
    LEFT JOIN guest g ON b.guestId = g.guestId
    LEFT JOIN application a ON b.applicationId = a.applicationId
    LEFT JOIN user u ON u.id = COALESCE(b.userId, a.userId)
    WHERE b.bookingId = ?
");
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
if (!$booking) {
    die("Booking not found.");
}
?>
<?php include("../dashboard/a_header.php"); ?>
<h2>Confirm Payment</h2>

<form id="paymentForm">
    <p><strong>Name:</strong> <?= htmlspecialchars($booking['name']) ?></p>
    <p><strong>Phone Number:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
    <p><strong>Total Amount:</strong> <?= htmlspecialchars($booking['totalAmount']) ?> Tk</p>

    <input type="hidden" name="bookingId" value="<?= $booking['bookingId'] ?>">
    <input type="hidden" name="attendantId" value="<?= $attendantId ?>">
    <input type="hidden" name="totalAmount" value="<?= $booking['totalAmount'] ?>">
    <input type="hidden" name="name" value="<?= htmlspecialchars($booking['name']) ?>">
    <input type="hidden" name="phone" value="<?= htmlspecialchars($booking['phone']) ?>">

    <label for="paid_amount">Enter Paid Amount:</label>
    <input type="number" name="paid_amount" id="paid_amount" required min="0">

    <br><br>
    <button type="submit">Confirm Payment</button>
</form>
<div id="message"></div>
<div id="slipContainer" style="display: none;">
    <div id="paymentSlip" style="max-width: 500px; margin: 30px auto; padding: 20px; border: 2px solid #444; border-radius: 10px; font-family: Arial;"></div>
</div>

<!-- html2pdf.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
document.getElementById('paymentForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('confirm_payment.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const messageDiv = document.getElementById('message');

        if (data.status === 'success') {
            messageDiv.innerHTML = '<p style="color:green;">✅ Payment confirmed.</p>';

            const slipHTML = `
                <h2 style="text-align:center; color:#2e6da4;">Guest House Payment Slip</h2>
                <hr>
                <p><strong>Name:</strong> ${data.name}</p>
                <p><strong>Phone:</strong> ${data.phone}</p>
                <p><strong>Total Amount:</strong> Tk ${data.totalAmount}</p>
                <p><strong>Paid Amount:</strong> Tk ${data.paidAmount}</p>
                <p><strong>Return Amount:</strong> Tk ${data.returnAmount}</p>
                <hr>
                <p style="text-align:center; color:green;">✅ Payment Confirmed</p>
                <p style="text-align:center;">Date: <strong>${data.date}</strong></p>
            `;

            const slipDiv = document.getElementById('paymentSlip');
            slipDiv.innerHTML = slipHTML;

            const slipContainer = document.getElementById('slipContainer');
            slipContainer.style.display = 'block';

            // Auto-download PDF
            html2pdf().from(slipDiv).set({
                filename: `PaymentSlip_${data.bookingId}.pdf`,
                margin: 0.5,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            }).save();
        } else {
            messageDiv.innerHTML = `<p style="color:red;">❌ ${data.message}</p>`;
        }
    })
    .catch(err => {
        document.getElementById('message').innerHTML = '<p style="color:red;">❌ Error confirming payment.</p>';
        console.error(err);
    });
});
</script>
