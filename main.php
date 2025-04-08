<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"])) {
  header("location: login.php");  // Changed back to login.php assuming that's your login page
  exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
  $choice = $_POST['pension_type'];
  $_SESSION['pension_choice'] = $choice;

  // Redirect based on choice
  if ($choice == "public") {
    header("location: public_companies.php");
    exit;
  } elseif ($choice == "private") {
    header("location: private_companies.php");
    exit;
  }
}
?>

<html>

<head>
  <title>Pension Choice</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
    body {
      background-color: lightblue;
      font-family: Verdana, sans-serif;
      text-align: center;
    }

    .container {
      width: 40%;
      margin: 50px auto;
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .radio-option {
      margin: 15px 0;
      font-size: 18px;
    }

    input[type="submit"] {
      width: 40%;
      height: 40px;
      margin-top: 20px;
      font-size: 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
    <h3>Please Select Your Pension Preference</h3>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <div class="radio-option">
        <input type="radio" id="private" name="pension_type" value="private" required>
        <label for="private">Private Company Pension</label>
      </div>

      <div class="radio-option">
        <input type="radio" id="public" name="pension_type" value="public">
        <label for="public">Public Company Pension</label>
      </div>

      <input type="submit" name="submit" value="Submit Choice">
    </form>

    <p style="margin-top: 20px;">
      <a href="logout.php" style="color: blue;">Logout</a>
    </p>
  </div>
</body>

</html>