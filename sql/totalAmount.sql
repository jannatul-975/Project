UPDATE booking b
JOIN (
    SELECT
        br.bookingId,
        SUM(DATEDIFF(b.checkOutDate, b.checkInDate) * r.pricePerNight) AS total
    FROM booking_room br
    JOIN booking b ON br.bookingId = b.bookingId
    JOIN room r ON br.RoomID = r.RoomID
    GROUP BY br.bookingId
) totals ON b.bookingId = totals.bookingId
SET b.totalAmount = totals.total;
