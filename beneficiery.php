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

if (!isset($_SESSION['selected_company_id'])) {
	header("Location: choose_company_2.php?next=beneficiery.php");
	exit;
}

$success = 0;
$msg = "";
$id = $_SESSION['sid'] ?? '';
$company_id = $_SESSION['selected_company_id'];

$filterquery = "SELECT fname FROM Pensioner WHERE company_id = ?";
if ($stmt = $mysqli->prepare($filterquery)) {
	$stmt->bind_param("i", $company_id);
	$stmt->execute();
	$filteredPensioners = $stmt->get_result();
	echo "<!-- Debug: Number of pensioners: " . $filteredPensioners->num_rows . " -->";
	$stmt->close();
} else {
	$msg = "Database error: " . $mysqli->error;
	$filteredPensioners = null;
	echo "<!-- Debug: Query error: " . $mysqli->error . " -->";
}

if (isset($_REQUEST['register'])) {
	echo "<!-- Debug: Form submitted -->";
	$fname = $_POST["fname"] ?? '';
	$first = $_POST["firstname"] ?? '';
	$father = $_POST["fathername"] ?? '';
	$last = $_POST["lastname"] ?? '';
	$dod = $_POST["dod"] ?? '';
	$bencat = $_POST["bencat"] ?? '';
	$dob = $_POST["dob"] ?? '';
	$share = $_POST["share"] ?? '';

	echo "<!-- Debug: POST data: " . print_r($_POST, true) . " -->";

	if (empty($fname) || empty($first) || empty($father) || empty($last) || empty($dod) || empty($bencat) || empty($dob) || empty($share)) {
		echo "<script>alert('Fill all required fields');</script>";
		echo "<!-- Debug: Empty field detected -->";
	} elseif ((!preg_match('/[a-zA-Z]/', trim($fname))) || (!preg_match('/[a-zA-Z]/', trim($first))) || (!preg_match('/[a-zA-Z]/', trim($father))) || (!preg_match('/[a-zA-Z]/', trim($last)))) {
		$msg = "Incorrect data. Please don’t fill numbers in text fields.";
		echo "<!-- Debug: Validation failed -->";
	} else {
		echo "<!-- Debug: Validation passed -->";
		$chksql = "SELECT * FROM beneficiery WHERE fname = ?";
		if ($stmt = $mysqli->prepare($chksql)) {
			$stmt->bind_param("s", $fname);
			$stmt->execute();
			$chksqlr = $stmt->get_result();
			if ($chksqlr->num_rows > 0) {
				$sum = 0;
				while ($chksqlf = $chksqlr->fetch_assoc()) {
					$sum += $chksqlf['benshare'];
				}
				echo "<!-- Debug: Existing beneficiaries found, sum = $sum -->";
				if ($sum == 100) {
					$qup = "DELETE FROM beneficiery WHERE fname = ?";
					if ($stmt2 = $mysqli->prepare($qup)) {
						$stmt2->bind_param("s", $fname);
						$stmt2->execute();
						$stmt2->close();
					}
					$msg = "Share already full. All Beneficiary data is deleted. Start again!";
				} elseif ($sum + $share > 100) {
					$qup = "DELETE FROM beneficiery WHERE fname = ?";
					if ($stmt2 = $mysqli->prepare($qup)) {
						$stmt2->bind_param("s", $fname);
						$stmt2->execute();
						$stmt2->close();
					}
					$msg = "Adjust the sum amount. All Beneficiary data is deleted. Start again!";
				} else {
					$query = "INSERT INTO beneficiery (fname, first, father, last, deathdate, bencat, benbirth, benshare, company_id) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
					if ($stmt2 = $mysqli->prepare($query)) {
						$stmt2->bind_param("ssssssssi", $fname, $first, $father, $last, $dod, $bencat, $dob, $share, $company_id);
						if ($stmt2->execute()) {
							echo "<script>alert('You Have Successfully Registered a new Beneficiary.');</script>";
							$success = 1;
							echo "<!-- Debug: Insert successful -->";
						} else {
							$msg = "Error registering beneficiary: " . $mysqli->error;
							echo "<!-- Debug: Insert failed: " . $mysqli->error . " -->";
						}
						$stmt2->close();
					} else {
						$msg = "Prepare error: " . $mysqli->error;
						echo "<!-- Debug: Prepare failed: " . $mysqli->error . " -->";
					}
				}
			} else {
				echo "<!-- Debug: No existing beneficiaries -->";
				$query = "INSERT INTO beneficiery (fname, first, father, last, deathdate, bencat, benbirth, benshare, company_id) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
				if ($stmt2 = $mysqli->prepare($query)) {
					$stmt2->bind_param("ssssssssi", $fname, $first, $father, $last, $dod, $bencat, $dob, $share, $company_id);
					if ($stmt2->execute()) {
						echo "<script>alert('You Have Successfully Registered a new Beneficiary.');</script>";
						$success = 1;
						echo "<!-- Debug: Insert successful -->";
					} else {
						$msg = "Error registering beneficiary: " . $mysqli->error;
						echo "<!-- Debug: Insert failed: " . $mysqli->error . " -->";
					}
					$stmt2->close();
				} else {
					$msg = "Prepare error: " . $mysqli->error;
					echo "<!-- Debug: Prepare failed: " . $mysqli->error . " -->";
				}
			}
			$stmt->close();
		} else {
			$msg = "Database error: " . $mysqli->error;
			echo "<!-- Debug: Check query failed: " . $mysqli->error . " -->";
		}
	}
}
?>

<html>

<head>
	<title>Beneficiary Registration</title>
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
			font-size: 20px;
		}

		input[type="submit"] {
			width: 30%;
			height: 40px;
			font-family: Verdana;
			font-size: 25px;
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
			<a href="choose_company.php?next=pensioner.php">Register new pensioner</a><br>
			<a href="choose_company_2.php?next=beneficiery.php">Register Beneficiary</a><br>
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
			<a href="choose_company.php?next=pensioner.php">Register new pensioner</a><br>
			<a href="report.php">Generate report</a><br>
			<a href="logout.php">Logout</a><br>
		<?php } else if ($roles['role'] == "Organization") { ?>
			<a href="choose_company.php?next=pensioner.php">Register new pensioner</a><br>
			<a href="report.php">Generate report</a><br>
			<a href="index.php">Logout</a><br>
		<?php } ?>
	</div>
	<div class="content">
		<form method="post" enctype="multipart/form-data" class="form-container">
			<table>
				<tr>
					<td colspan="3" class="HeaderColor">
						<h2>Beneficiary Registration</h2>
						<hr color="blue">
						<font color="#FF0000" align="center"><?php echo htmlspecialchars($msg); ?></font>
					</td>
				</tr>
				<tr>
					<td class="LabelColor" nowrap="nowrap"><label for="fname">Pensioner Full Name</label></td>
					<td colspan="2" class="TitleColor">
						<select id="fname" name="fname" required>
							<?php if (isset($_POST['register'])) { ?>
								<option value="<?php echo htmlspecialchars($_POST['fname']); ?>">
									<?php echo htmlspecialchars($_POST['fname']); ?>
								</option>
							<?php } ?>
							<?php
							if ($filteredPensioners && $filteredPensioners->num_rows > 0) {
								while ($plistf = $filteredPensioners->fetch_assoc()) { ?>
									<option value="<?php echo htmlspecialchars($plistf['fname']); ?>">
										<?php echo htmlspecialchars($plistf['fname']); ?>
									</option>
								<?php }
							} else { ?>
								<option value="" disabled>No pensioners in this company</option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="LabelColor" nowrap="nowrap">First Name:</td>
					<td colspan="2" class="TitleColor">
						<input type="text" id="first" name="firstname" value="<?php echo htmlspecialchars($_POST['firstname'] ?? ''); ?>" required />
					</td>
				</tr>
				<tr>
					<td class="LabelColor" nowrap="nowrap">Father's Name:</td>
					<td colspan="2" class="TitleColor">
						<input type="text" id="father" name="fathername" value="<?php echo htmlspecialchars($_POST['fathername'] ?? ''); ?>" required />
					</td>
				</tr>
				<tr>
					<td class="LabelColor" nowrap="nowrap">Last Name:</td>
					<td colspan="2" class="TitleColor">
						<input type="text" id="last" name="lastname" value="<?php echo htmlspecialchars($_POST['lastname'] ?? ''); ?>" required />
					</td>
				</tr>
				<tr>
					<td class="LabelColor">Pensioner Date of Death</td>
					<td colspan="2" class="TitleColor">
						<input type="date" id="dod" name="dod" value="<?php echo htmlspecialchars($_POST['dod'] ?? ''); ?>" required />
					</td>
				</tr>
				<tr>
					<td class="LabelColor">Beneficiary Category</td>
					<td colspan="2" class="TitleColor">
						<select name="bencat" required>
							<?php if (isset($_POST['register'])) { ?>
								<option value="<?php echo htmlspecialchars($_POST['bencat']); ?>">
									<?php echo htmlspecialchars($_POST['bencat']); ?>
								</option>
							<?php } ?>
							<option value="Spouse">Spouse</option>
							<option value="Child">Child</option>
							<option value="Father">Father</option>
							<option value="Mother">Mother</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="LabelColor">Beneficiary Birth Date</td>
					<td colspan="2" class="TitleColor">
						<input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>" required />
					</td>
				</tr>
				<tr>
					<td class="LabelColor" nowrap="nowrap"><label for="share">Beneficiary Share in Percent</label></td>
					<td colspan="2" class="TitleColor">
						<input name="share" type="number" min="1" max="100" value="<?php echo htmlspecialchars($_POST['share'] ?? ''); ?>" required><span>%</span>
					</td>
				</tr>
				<tr class="FooterColor">
					<td></td>
					<td colspan="3">
						<input type="submit" name="register" value="Register" />
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>

</html>