<?php
include 'db.php';

// Query to fetch feedback with user details
$sql = "SELECT f.rating, f.comment, f.date, u.name, u.profile_pic
        FROM feedback f
        JOIN user u ON f.userId = u.id
        ORDER BY f.date DESC";  // Sort by date (most recent first)

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Feedback</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      margin-top: 30px;
      padding: 20px;
    }

    .feedback-container {
      max-width: 900px;
      margin: auto;
    }

    .feedback-card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: flex-start;
      padding: 20px;
      margin-bottom: 20px;
      gap: 20px;
    }

    .profile-pic {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ccc;
    }

    .feedback-content {
      flex: 1;
    }

    .name {
      font-weight: bold;
      font-size: 1.2em;
      margin-bottom: 5px;
    }

    .stars {
      color: gold;
      font-size: 1.1em;
      margin-bottom: 5px;
    }

    .comment {
      margin-top: 10px;
      color: #333;
    }

    .feedback-date {
      font-size: 0.9em;
      color: #777;
      margin-top: 10px;
    }

    .no-feedback {
      text-align: center;
      font-size: 1.2em;
      color: gray;
      margin-top: 100px;
    }
  </style>
</head>
<?php include("header.php")?>
<script src="script.js"></script>
<body>
  <div class="feedback-container">
    <h1 style="text-align:center; color:green;">User Feedback</h1>

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="feedback-card">
          <img class="profile-pic" src="../Project/user/profile_pics/<?= htmlspecialchars($row['profile_pic']) ?>" alt="Profile Picture">
          <div class="feedback-content">
            <div class="name"><?= htmlspecialchars($row['name']) ?></div>
            <div class="stars"><?= str_repeat('★', intval($row['rating'])) . str_repeat('☆', 5 - intval($row['rating'])) ?></div>
            <?php if (!empty($row['comment'])): ?>
              <div class="comment"><?= nl2br(htmlspecialchars($row['comment'])) ?></div>
            <?php endif; ?>
            <div class="feedback-date">
              <?php 
                // Format date to day-month-year
                $date = new DateTime($row['date']);
                echo $date->format('d-m-Y');
              ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="no-feedback">No feedback submitted yet.</div>
    <?php endif; ?>

  </div>
  <?php include("footer_index.php")?>
</body>
</html>

<?php $conn->close(); ?>
