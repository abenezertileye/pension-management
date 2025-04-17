<?php
session_start();
include_once('connection.php');
error_reporting(1);

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
} else {
  $now = time();
  if ($now > $_SESSION['expire']) {
    session_destroy();
    header("Location: login.php");
    exit;
  }
}

// Ensure a company was selected
if (!isset($_SESSION['selected_company_id'])) {
  header("Location: choose_company.php");
  exit;
}

// Ensure organization type was chosen
if (!isset($_SESSION['pension_choice']) || !in_array($_SESSION['pension_choice'], ['public', 'private'])) {
  header("Location: pension_choice.php");
  exit;
}

$success = 0;
$sex = $_POST["sex"] ?? '';
$bod = $_POST["dob"] ?? '';
$mstatus = $_POST["mstatus"] ?? '';
$salary = $_POST["salary"] ?? '';
$service = $_POST["service"] ?? '';
$password = $_POST["password"] ?? '';
$rdate = date("Y-m-d");
$first = $_POST["firstname"] ?? '';
$father = $_POST["fathername"] ?? '';
$last = $_POST["lastname"] ?? '';
$type = $_SESSION['pension_choice'];
$company_id = $_SESSION['selected_company_id'];

if (isset($_REQUEST['register'])) {
  if (empty($first) || empty($father) || empty($last) || empty($sex) || empty($bod) || empty($mstatus) || empty($salary) || empty($service) || empty($password)) {
    echo "<script>alert('Fill all required fields');</script>";
  } else if ((!preg_match('/[a-zA-Z]/', trim($first))) || (!preg_match('/[a-zA-Z]/', trim($father))) || (!preg_match('/[a-zA-Z]/', trim($last)))
    || (!preg_match('/[a-zA-Z]/', trim($mstatus)))
  ) {
    $msg = "Incorrect data. Please don’t fill numbers in text fields.";
  } else if (strlen($password) < 8) {
    $msg = "Password must be at least 8 characters long.";
  } else {
    $fname = "$first $father $last";
    $qfile = $_FILES['photo']['name'] ?? '';
    $tname = $_FILES['photo']['tmp_name'] ?? '';
    $folder = $qfile;

    $query = "SELECT * FROM pensioner ORDER BY id DESC LIMIT 1";
    $result = $mysqli->query($query);
    if ($result->num_rows >= 1) {
      $t = $result->fetch_assoc();
      $last_id = $t['id'];
      if ($type == 'public') {
        $orgid = "Pub" . ($last_id + 1);
        $ssno = $orgid . ($last_id + 3);
      } else {
        $orgid = "Pri" . ($last_id + 1);
        $ssno = $orgid . ($last_id + 3);
      }
    } else {
      if ($type == 'public') {
        $orgid = "Pub11";
        $ssno = "Pub1113";
      } else {
        $orgid = "Pri11";
        $ssno = "Pri1113";
      }
    }

    // Prepare and execute the insert query without hashing password
    $query = "INSERT INTO Pensioner (ssn, fname, sex, bod, orgtype, mstatus, salary, service, rdate, orgid, photo, company_id, password) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("sssssssssssss", $ssno, $fname, $sex, $bod, $type, $mstatus, $salary, $service, $rdate, $orgid, $qfile, $company_id, $password);
      if ($stmt->execute()) {
        move_uploaded_file($tname, $folder);
        echo "<script>alert('You have successfully registered a new pensioner');</script>";
        $success = 1;
      } else {
        $msg = "Error registering pensioner: " . $mysqli->error;
      }
      $stmt->close();
    } else {
      $msg = "Database error: " . $mysqli->error;
    }
  }
}
?>

<html>

<head>
  <title>Pensioner Registration</title>
  <style>
    a:hover {
      color: black;
      background-color: white;
    }

    a {
      text-decoration: none;
    }

    body {
      background-color: lightblue;
      margin: 0;
    }

    .header {
      width: 100%;
      background-color: gray;
      margin-bottom: 10px;
    }

    .header img {
      width: 100%;
      display: block;
    }

    .sidebar {
      padding: 20px;
      width: 20%;
      background-color: #E1F8DC;
      text-align: center;
      font-family: Verdana;
      font-size: 18px;
      margin-top: 50px;
      border-radius: 0;
      float: left;
      line-height: 50px;
      border: solid blue 1px;
      min-height: calc(100vh - 70px);
    }

    .sidebar a {
      float: left;
      color: blue;
    }

    .content {
      float: right;
      width: 70%;
      padding: 10px;
      margin-top: 50px;
    }

    .form-container {
      background-color: white;
      width: 100%;
      margin: auto;
      font-family: Verdana;
      font-size: 25px;
      border-radius: 15px;
    }

    table {
      width: 99%;
      border: 0;
      cellpadding: 8;
      cellspacing: 10;
      font-family: Verdana;
      font-size: 23px;
    }

    .HeaderColor h2 {
      text-align: center;
      background-color: #D9D9D9;
      width: 100%;
    }

    .LabelColor {
      text-align: right;
    }

    .TitleColor input,
    .TitleColor select {
      width: 70%;
      height: 30px;
      font-size: 25px;
    }

    input[type="submit"],
    input[type="reset"] {
      width: 30%;
      height: 40px;
      font-family: Verdana;
      font-size: 25px;
    }

    .password-container {
      position: relative;
      width: 70%;
    }

    .password-container input {
      width: 100%;
      height: 30px;
      font-size: 25px;
    }

    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 20px;
    }
  </style>
</head>

<body>
  <div class="header">
    <img src="pssa.jpg" style="width:100%;">
  </div>
  <?php
  $user = $_SESSION['user'];
  $sql = "SELECT role FROM users WHERE username = ?";
  if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $roles = $result->fetch_assoc();
    $stmt->close();
  }
  ?>
  <div class="sidebar">
    <?php if ($roles['role'] == "Admin") { ?>
      <a href="register_company.php">Register new company</a><br>
      <a href="choose_company.php">Register new pensioner</a><br>
      <a href="choose_company_2.php">Register Beneficiery</a><br>
      <a href="create.php">Add new user</a><br>
      <a href="report.php">Generate report</a><br>
      <a href="viewfeed.php">View feedbacks</a><br>
      <a href="calculate.php">Calculate Pension</a><br>
      <a href="logout.php">Logout</a><br>
    <?php } else if ($roles['role'] == "Pensioner") { ?>
      <a href="feedback.php">Send feedback</a><br>
      <a href="report.php">Generate report</a><br>
      <a href="logout.php">Logout</a><br>
    <?php } else if ($roles['role'] == "Clerk") { ?>
      <a href="choose_company.php">Register new pensioner</a><br>
      <a href="report.php">Generate report</a><br>
      <a href="logout.php">Logout</a><br>
    <?php } else if ($roles['role'] == "Organization") { ?>
      <a href="choose_company.php">Register new pensioner</a><br>
      <a href="report.php">Generate report</a><br>
      <a href="index.php">Logout</a><br>
    <?php } ?>
  </div>
  <div class="content">
    <form method="post" enctype="multipart/form-data" class="form-container">
      <table>
        <tr>
          <td colspan="3" class="HeaderColor">
            <h2>Register New Pensioner</h2>
            <hr color="blue">
            <font color="#FF0000" align="center"><?php echo $msg ?? ''; ?></font>
          </td>
        </tr>
        <tr>
          <td class="LabelColor" nowrap="nowrap"><label for="firstname">First Name</label></td>
          <td colspan="2" class="TitleColor"><input type="text" id="firstname" name="firstname" required /></td>
        </tr>
        <tr>
          <td class="LabelColor" nowrap="nowrap"><label for="fathername">Father Name</label></td>
          <td colspan="2" class="TitleColor"><input type="text" id="fathername" name="fathername" required /></td>
        </tr>
        <tr>
          <td class="LabelColor" nowrap="nowrap"><label for="lastname">Last Name</label></td>
          <td colspan="2" class="TitleColor"><input type="text" id="lastname" name="lastname" required /></td>
        </tr>
        <tr>
          <td class="LabelColor">Gender</td>
          <td colspan="2">
            <label for="male">Male</label><input type="radio" id="male" name="sex" value="M" />
            <label for="female">Female</label><input type="radio" id="female" name="sex" value="F" />
          </td>
        </tr>
        <tr>
          <td class="LabelColor">Birth Date</td>
          <td colspan="2" class="TitleColor">
            <input type="date" id="day" name="dob" onchange="validateAge()" required />
            <span id="dob-error" style="color: red; display: none;">You must be at least 22 years old.</span>
          </td>
        </tr>
        <tr>
          <td class="LabelColor" nowrap="nowrap"><label for="mstatus">Marital Status</label></td>
          <td colspan="2" class="TitleColor">
            <select id="mstatus" name="mstatus">
              <option>Married</option>
              <option>Unmarried</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="LabelColor" nowrap="nowrap"><label for="salary">Last 3 years avg. salary</label></td>
          <td colspan="2" class="TitleColor"><input name="salary" type="number" max="100000" required></td>
        </tr>
        <tr>
          <td class="LabelColor" nowrap="nowrap"><label for="service">Years of service</label></td>
          <td colspan="2" class="TitleColor"><input name="service" type="number" max="50" required></td>
        </tr>
        <tr>
          <td class="LabelColor" nowrap="nowrap"><label for="photo">Photo</label></td>
          <td colspan="2" class="TitleColor"><input name="photo" type="file" required></td>
        </tr>
        <tr>
          <td class="LabelColor" nowrap="nowrap"><label for="password">Password</label></td>
          <td colspan="2" class="TitleColor">
            <div class="password-container">
              <input type="password" id="password" name="password" required minlength="8">
              <span class="password-toggle" onclick="togglePassword()">👁️</span>
            </div>
          </td>
        </tr>
        <tr class="FooterColor">
          <td></td>
          <td colspan="3">
            <input type="submit" name="register" value="Register" />
            <input type="reset" name="Submit" value="Reset" />
          </td>
        </tr>
      </table>
    </form>
  </div>

  <script>
    function validateAge() {
      const dobInput = document.getElementById('day');
      const errorSpan = document.getElementById('dob-error');
      const dob = new Date(dobInput.value);
      const today = new Date();
      const minDate = new Date();
      minDate.setFullYear(minDate.getFullYear() - 22);

      if (dob > minDate) {
        const formattedMinDate = minDate.toISOString().split("T")[0];
        errorSpan.textContent = `Birth date should be on or before ${formattedMinDate}`;
        errorSpan.style.display = 'inline';
        dobInput.setCustomValidity("Invalid birth date");
      } else {
        errorSpan.style.display = 'none';
        dobInput.setCustomValidity("");
      }
    }

    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleButton = document.querySelector('.password-toggle');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.textContent = '🙈';
      } else {
        passwordInput.type = 'password';
        toggleButton.textContent = '👁️';
      }
    }
  </script>
</body>

</html>