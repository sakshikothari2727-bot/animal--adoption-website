<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'paws';

$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
$success_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $animal_name = $_POST['animal'];
    $experience = $_POST['experience'];
    $why = $_POST['why'];

    // Get animal_id by name
    $stmt = mysqli_prepare($conn, "SELECT id FROM animals WHERE name = ?");
    mysqli_stmt_bind_param($stmt, "s", $animal_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $animal_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($animal_id) {
        // Insert adoption record
        $stmt = mysqli_prepare($conn, "INSERT INTO adoptions (full_name, email, phone, address, animal_id, experience, why_adopt) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssiss", $full_name, $email, $phone, $address, $animal_id, $experience, $why);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Update animal status
        $stmt = mysqli_prepare($conn, "UPDATE animals SET status = 'adopted' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $animal_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $success_message = "🎉 Thank you, $full_name! Your adoption request for $animal_name has been submitted.";
    } else {
        $success_message = "❌ Error: Selected animal not found.";
    }
}

// Fetch available animals
$animals = [];
$result = mysqli_query($conn, "SELECT * FROM animals WHERE status = 'available'");
while ($row = mysqli_fetch_assoc($result)) {
    $animals[] = $row;
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Adopt an Animal</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fefbf6;
      padding: 2rem;
    }
    .form-container {
      background-color: white;
      max-width: 600px;
      margin: auto;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #ff914d;
    }
    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 1rem;
      border-radius: 5px;
      margin-bottom: 1rem;
      border: 1px solid #c3e6cb;
    }
    label {
      display: block;
      margin: 1rem 0 0.5rem;
      font-weight: 600;
    }
    input, select, textarea {
      width: 100%;
      padding: 0.7rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    button {
      background-color: #ff914d;
      color: white;
      padding: 0.8rem 1.2rem;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      margin-top: 1.5rem;
      cursor: pointer;
      width: 100%;
    }
    button:hover {
      background-color: #e37329;
    }
    .description {
      margin-top: 0.5rem;
      font-size: 0.9rem;
      color: #555;
      font-style: italic;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>🐾 Animal Adoption Form</h2>

    <?php if ($success_message): ?>
      <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form action="adoptform.php" method="POST">
      <label for="fullname">Full Name:</label>
      <input type="text" id="fullname" name="fullname" required>

      <label for="email">Email Address:</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Phone Number:</label>
      <input type="tel" id="phone" name="phone" required>

      <label for="address">Home Address:</label>
      <textarea id="address" name="address" rows="3" required></textarea>

      <label for="animal">Animal You Want to Adopt:</label>
      <select id="animal" name="animal" required onchange="showDescription(this.value)">
        <option value="">-- Select an Animal --</option>
        <?php foreach ($animals as $a): ?>
            <option value="<?php echo htmlspecialchars($a['name']); ?>">
                <?php echo htmlspecialchars($a['name']) . ' (' . htmlspecialchars($a['type']) . ')'; ?>
            </option>
        <?php endforeach; ?>
      </select>

      <div id="animalDescription" class="description"></div>

      <label for="experience">Do you have any experience with pets?</label>
      <textarea id="experience" name="experience" rows="3" required></textarea>

      <label for="why">Why do you want to adopt?</label>
      <textarea id="why" name="why" rows="3" required></textarea>

      <button type="submit">Submit Application</button>
    </form>
  </div>

  <script>
    const animalData = <?php echo json_encode($animals); ?>;

    function showDescription(selectedName) {
      const animal = animalData.find(a => a.name === selectedName);
      const descDiv = document.getElementById('animalDescription');
      if (animal) {
        descDiv.textContent = "📋 " + animal.description;
      } else {
        descDiv.textContent = "";
      }
    }
  </script>

</body>
</html>
