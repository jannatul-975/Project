<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Feedback Form</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }

    .container {
      max-width: 600px;
      margin: 60px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: rgb(16, 95, 27);
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }

    .rating {
      direction: rtl;
      unicode-bidi: bidi-override;
      display: inline-flex;
      margin-top: 10px;
    }

    .rating input {
      display: none;
    }

    .rating label {
      font-size: 2em;
      color: lightgray;
      cursor: pointer;
      transition: color 0.2s;
    }

    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
      color: gold;
    }

    textarea {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 1em;
      resize: vertical;
    }

    .btn-wrapper {
      text-align: center;
    }

    .btn {
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 1em;
      color: white;
      background-color: rgb(16, 95, 27);
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .btn:hover {
      background-color: rgb(7, 71, 15);
    }

    .message {
      text-align: center;
      margin-top: 15px;
      font-weight: bold;
    }

    .message.success {
      color: green;
    }

    .message.error {
      color: red;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Feedback Form</h1>
    <form id="feedbackForm">
      <label for="rating">Rating:</label>
      <div class="rating">
        <input type="radio" name="rating" id="star5" value="5" /><label for="star5">&#9733;</label>
        <input type="radio" name="rating" id="star4" value="4" /><label for="star4">&#9733;</label>
        <input type="radio" name="rating" id="star3" value="3" /><label for="star3">&#9733;</label>
        <input type="radio" name="rating" id="star2" value="2" /><label for="star2">&#9733;</label>
        <input type="radio" name="rating" id="star1" value="1" /><label for="star1">&#9733;</label>
      </div>

      <label for="comment">Comment:</label>
      <textarea name="comment" id="comment" rows="5" placeholder="Write your feedback here..."></textarea>

      <div class="btn-wrapper">
        <button type="submit" class="btn">Submit Feedback</button>
      </div>
      <div id="responseMessage" class="message"></div>
    </form>
  </div>

  <script>
    document.getElementById("feedbackForm").addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const responseMessage = document.getElementById("responseMessage");

      fetch("submit_feedback.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.text())
      .then(text => {
        responseMessage.textContent = text;

        // Simple keyword check to classify success or error
        if (text.toLowerCase().includes("error") || text.toLowerCase().includes("please") || text.toLowerCase().includes("not")) {
          responseMessage.className = "message error";
        } else {
          responseMessage.className = "message success";
          document.getElementById("feedbackForm").reset();
        }
      })
      .catch(error => {
        responseMessage.textContent = "An error occurred while submitting the feedback.";
        responseMessage.className = "message error";
      });
    });
  </script>
</body>
</html>
