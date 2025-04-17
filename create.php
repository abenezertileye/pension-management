<?php
session_start();
include_once('connection.php');
error_reporting(1);
error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

if (!isset($_SESSION['user'])) {
	header("Location:login.php");
} else {
	$now = time();
	if ($now > $_SESSION['expire']) {
		session_destroy();
	}
}

$full_err = $role_err = $pass_err = $errmsg = "";
if (isset($_POST['submit'])) {
	if (empty(trim($_POST["fullname"]))) {
		$full_err = "Please enter full name";
	} else {
		$full = trim($_POST["fullname"]);
	}
	if (empty(trim($_POST["role"]))) {
		$role_err = "Please choose the role";
	} else {
		$role = trim($_POST["role"]);
	}
	if (empty(trim($_POST["password"]))) {
		$pass_err = "Please enter a password";
	} elseif (strlen(trim($_POST["password"])) < 8) {
		$pass_err = "Password must be at least 8 characters long";
	} else {
		$pass = trim($_POST["password"]);
	}

	// Skip the check for existing users to allow duplicate usernames
	if (empty($full_err) && empty($role_err) && empty($pass_err)) {
		$sqlcreate = "INSERT INTO users (username, password, role, status) VALUES (?, ?, ?, 0)";
		if ($stmt_create = $mysqli->prepare($sqlcreate)) {
			$stmt_create->bind_param("sss", $full, $pass, $role);
			if ($stmt_create->execute()) {
				$errmsg = "Account created successfully";
			} else {
				$errmsg = "Error creating account: " . $mysqli->error;
			}
			$stmt_create->close();
		} else {
			$errmsg = "Database error: " . $mysqli->error;
		}
	}
}
?>

<html>

<head>
	<title>Account creation</title>
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
			font-family: verdana;
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
			font-family: verdana;
			font-size: 23px;
			border-radius: 15px;
			padding: 20px;
			line-height: 45px;
		}

		.form-container input,
		.form-container select {
			width: 70%;
			height: 30px;
			font-size: 23px;
		}

		.form-container input[type="submit"] {
			width: 50%;
			height: 30px;
			font-size: 23px;
		}

		.error {
			color: red;
			border: solid blue 1px;
			padding: 5px;
		}

		.password-container {
			position: relative;
			width: 70%;
		}

		.password-container input {
			width: 100%;
			height: 30px;
			font-size: 23px;
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
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();
	$roles = $result->fetch_assoc();
	$stmt->close();
	?>
	<div class="sidebar">
		<?php if ($roles['role'] == "Admin") { ?>
			<a href="register_company.php">Register new company</a><br>
			<a href="choose_company.php">Register new pensioner</a><br>
			<a href="choose_company_2.php">Register Beneficiary</a><br>
			<a href="create.php">Add new user</a><br>
			<a href="report.php">Generate report</a><br>
			<a href="viewfeed.php">View feedbacks</a><br>
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
			<a href="logout.php">Logout</a><br>
		<?php } ?>
	</div>
	<div class="content">
		<form method="post" enctype="multipart/form-data" action="create.php" class="form-container">
			<p class="error">
			<h3>Create account</h3>
			<?php echo htmlspecialchars($errmsg . " " . $full_err . " " . $role_err . " " . $pass_err); ?>
			</p>
			<hr>
			<br>
			<label for="fullname">Fullname</label><br>
			<input name="fullname" type="text"><br>
			<label for="role">Role</label><br>
			<select name="role">
				<option value="">Select Role</option>
				<option value="Admin">Admin</option>
				<option value="Clerk">Clerk</option>
			</select><br>
			<label for="password">Password</label><br>
			<div class="password-container">
				<input type="password" id="password" name="password" minlength="8">
				<span class="password-toggle" onclick="togglePassword()">👁️</span>
			</div><br><br>
			<input name="submit" type="submit" value="Create">
		</form>
	</div>
	<script>
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