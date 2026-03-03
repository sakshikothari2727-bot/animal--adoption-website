<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paws";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch animals that are available for adoption (status = 'available')
$sql = "SELECT * FROM animals WHERE status = 'available'";
$result = $conn->query($sql);

// Check if there are available animals
$animals = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $animals[] = $row;
    }
}

$conn->close(); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Adopt an Animal</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fefbf6;
      padding: 2rem;
      margin: 0;
    }
    .container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .animal-card {
      background-color: white;
      padding: 1rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 280px;
      text-align: center;
    }
    .animal-card img {
      width: 100%;
      max-height: 220px;
      object-fit: cover;
      border-radius: 5px;
    }
    .animal-card h3 {
      font-size: 1.2rem;
      color: #ff914d;
    }
    .adopt-button {
      background-color: #ff914d;
      color: white;
      padding: 0.5rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }
    .adopt-button:hover {
      background-color: #e37329;
    }
  </style>
</head>
<body>

  <h1 style="text-align: center;">Adopt an Animal</h1>

  <div class="container">
    <?php foreach ($animals as $animal): ?>
      <div class="animal-card">
        <img src="<?php echo htmlspecialchars($animal['image_url']); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>">
        <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
        <a href="adoptform.php?animal_id=<?php echo $animal['id']; ?>" class="adopt-button">Adopt <?php echo htmlspecialchars($animal['name']); ?></a>
      </div>
    <?php endforeach; ?>
  </div>

</body>
</html>
