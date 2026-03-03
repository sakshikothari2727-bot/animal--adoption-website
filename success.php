<?php
// Start session to handle potential user messages
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Adoption Successful</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fefbf6;
      padding: 2rem;
      margin: 0;
    }
    .success-container {
      background-color: white;
      max-width: 600px;
      margin: auto;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
    }
    h2 {
      color: #4CAF50;
    }
    p {
      font-size: 1.1rem;
      color: #555;
    }
    a {
      color: #ff914d;
      text-decoration: none;
      font-weight: 600;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="success-container">
    <h2>🐾 Adoption Successful! 🎉</h2>
    <p>Thank you for adopting an animal! Your adoption request has been processed successfully.</p>
    <p>We will contact you soon with more details about the adoption.</p>
    <p><a href="adopt.html">Go back to the adoption page</a></p>
  </div>

</body>
</html>
