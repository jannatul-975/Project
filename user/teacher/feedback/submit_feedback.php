<?php
include '../../../db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["user_id"])) {
        echo "User not logged in.";
        exit;
    }

    $userId = intval($_SESSION["user_id"]);
    $rating = isset($_POST["rating"]) && $_POST["rating"] !== '' ? intval($_POST["rating"]) : null;
    $comment = isset($_POST["comment"]) ? trim($_POST["comment"]) : '';

    // Allow submission only if at least a rating or a comment is provided
    if ($rating === null && $comment === '') {
        echo "Please provide at least a rating or a comment.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO feedback (userId, rating, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $userId, $rating, $comment);

    if ($stmt->execute()) {
        if ($rating === 5) {
            echo "Excellent Experience";
        } else if ($rating === 4) {
            echo "Very Good Experience";
        } else if ($rating === 3) {
            echo "Good Experience";
        } else if ($rating < 3 && $rating >= 1) {
            echo "Very Poor Experience. What can we do for you?";
        } else {
            echo "Your feedback has been submitted.You may give us rating";
        }
    } else {
        echo "Error submitting feedback: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
