<?php
session_start();
include_once('connection.php');
error_reporting(1);

if (!isset($_SESSION['user'])) {
	header("Location:login.php");
	exit;
} else {
	$now = time();
	if ($now > $_SESSION['expire']) {
		session_destroy();
		header("Location:login.php");
		exit;
	}
}

$success = 0;
$msg = "";
$id = $_SESSION['sid'];

if (@$_REQUEST['register']) {
	$fname = $_POST["fname"] ?? '';
	$first = $_POST["firstname"] ?? '';
	$father = $_POST["fathername"] ?? '';
	$last = $_POST["lastname"] ?? '';
	$dod = $_POST["dod"] ?? '';
	$bencat = $_POST["bencat"] ?? '';
	$dob = $_POST["dob"] ?? '';
	$share = $_POST["share"] ?? '';

	// Check if any required fields are empty (removed bename)
	if (empty($fname) || empty($first) || empty($father) || empty($last) || empty($dod) || empty($bencat) || empty($dob) || empty($share)) {
		echo "<script>alert('Fill all required fields');</script>";
	}
	// Validate that text fields contain only letters
	else if ((!preg_match('/[a-zA-Z]/', trim($fname))) || (!preg_match('/[a-zA-Z]/', trim($first))) || (!preg_match('/[a-zA-Z]/', trim($father))) || (!preg_match('/[a-zA-Z]/', trim($last)))) {
		$msg = "Incorrect data. Please don’t fill numbers in text fields.";
	} else {
		// Removed bename from duplicate check; now only checking fname
		$chksql = "SELECT * FROM beneficiery WHERE fname='$fname'";
		$chksqlr = $mysqli->query($chksql) or die($mysqli->error);
		if ($chksqlr->num_rows > 0) {
			$sum = 0;
			while ($chksqlf = $chksqlr->fetch_assoc()) {
				$sum += $chksqlf['benshare'];
			}
			if ($sum == 100) {
				$qup = "DELETE FROM beneficiery WHERE fname='$fname'";
				$mysqli->query($qup) or die($mysqli->error);
				$msg = "Share already full. All Beneficiary data is deleted. Start again!";
			} else if ($sum + $share > 100) {
				$qup = "DELETE FROM beneficiery WHERE fname='$fname'";
				$mysqli->query($qup) or die($mysqli->error);
				$msg = "Adjust the sum amount. All Beneficiary data is deleted. Start again!";
			} else {
				// Insert without bename
				$query = "INSERT INTO beneficiery (fname, first, father, last, deathdate, bencat, benbirth, benshare) 
                          VALUES ('$fname', '$first', '$father', '$last', '$dod', '$bencat', '$dob', '$share')";
				$result = $mysqli->query($query) or die($mysqli->error);
				if ($result) {
					echo "<script>alert('You Have Successfully Registered a new Beneficiary.');</script>";

					$success = 1;
				} else {
					$msg = "Error registering beneficiary. Please try again.";
				}
			}
		} else {
			// Insert without bename
			$query = "INSERT INTO beneficiery (fname, first, father, last, deathdate, bencat, benbirth, benshare) 
                      VALUES ('$fname', '$first', '$father', '$last', '$dod', '$bencat', '$dob', '$share')";
			$result = $mysqli->query($query) or die($mysqli->error);
			if ($result) {
				$msg = "You Have Successfully Registered a new Beneficiary";
				$success = 1;
			} else {
				$msg = "Error registering beneficiary. Please try again.";
			}
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
	</style>
</head>

<body bgcolor="lightblue">
	<div style="width:100%;background-color:gray;margin-bottom:10px;">
		<img src="pssa.jpg" style="width:100%;">
	</div>
	<?php
	$user = $_SESSION['user'];
	$sql = "SELECT role FROM users WHERE username='$user'";
	$result = $mysqli->query($sql);
	$roles = $result->fetch_assoc();
	?>
	<div style="padding:20px;width:20%;margin:auto;background-color:E1F8DC;text-align:center;font-family:verdana;font-size:18px;
                margin-top:50px;border-radius:0px;float:left;line-height:50px;border:solid blue 1px;height:100%;">
		<?php if ($roles['role'] == "Admin") { ?>
			<a href="pensioner.php" style="float:left;">Register new pensioner</a><br>
			<a href="beneficiery.php" style="float:left;">Register Beneficiary</a><br>
			<a href="create.php" style="float:left;">Add new user</a><br>
			<a href="report.php" style="float:left;">Generate report</a><br>
			<a href="viewfeed.php" style="float:left;">View feedbacks</a><br>
			<a href="calculate.php" style="float:left;">Calculate Pension</a><br>
			<a href="logout.php" style="float:left;">Logout</a><br>
		<?php } else if ($roles['role'] == "Pensioner") { ?>
			<a href="feedback.php" style="float:left;">Send feedback</a><br>
			<a href="report.php" style="float:left;">Generate report</a><br>
			<a href="logout.php" style="float:left;">Logout</a><br>
		<?php } else if ($roles['role'] == "Clerk") { ?>
			<a href="pensioner.php" style="float:left;">Register new pensioner</a><br>
			<a href="report.php" style="float:left;">Generate report</a><br>
			<a href="logout.php" style="float:left;">Logout</a><br>
		<?php } else if ($roles['role'] == "Organization") { ?>
			<a href="pensioner.php" style="float:left;">Register new pensioner</a><br>
			<a href="report.php" style="float:left;">Generate report</a><br>
			<a href="index.php" style="float:left;">Logout</a><br>
		<?php } ?>
	</div>
	<div style="float:right;width:70%;padding:10px;margin-top:50px;">
		<form method="post" enctype="multipart/form-data" style="background-color:white;width:100%;margin:auto;font-family:verdana;font-size:25px;border-radius:15px;">
			<table width="99%" border="0" cellpadding="8" cellspacing="10" style="font-family:verdana;font-size:23px;">
				<tr>
					<td colspan="3" class="HeaderColor">
						<h2 style="text-align:center;background-color:#D9D9D9;width:100%;">Beneficiary Registration</h2>
						<hr color=blue>
						<font color="#FF0000" align=center><?php echo $msg; ?></font>
					</td>
				</tr>
				<?php
				$sq = "SELECT * FROM pensioner WHERE fp=0";
				$plist = $mysqli->query($sq) or die($mysqli->error);
				?>
				<tr style="vertical-align: top">
					<td style="text-align: right" class="LabelColor" nowrap="nowrap">
						<label for="fname">Pensioner Full Name</label>
					</td>
					<td colspan="2" class="TitleColor">
						<select style="width:70%;height:30;font-family:verdana;font-size:20px;" id="fname" name="fname">
							<?php if (isset($_POST['register']) || isset($_POST['update'])) { ?>
								<option><?php echo $_POST['fname']; ?></option>
							<?php } ?>
							<?php while ($plistf = $plist->fetch_assoc()) { ?>
								<option><?php echo $plistf['fname']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr style="vertical-align: top">
					<td style="text-align: right" class="LabelColor" nowrap="nowrap">First Name:</td>
					<td colspan="2" class="TitleColor">
						<input style="width:70%;height:30px;font-family:verdana;font-size:20px;" type="text" id="first" name="firstname"
							value="<?php if (isset($_POST['register']) || isset($_POST['update'])) echo $_POST['firstname']; ?>" />
					</td>
				</tr>
				<tr style="vertical-align: top">
					<td style="text-align: right" class="LabelColor" nowrap="nowrap">Father's Name:</td>
					<td colspan="2" class="TitleColor">
						<input style="width:70%;height:30px;font-family:verdana;font-size:20px;" type="text" id="father" name="fathername"
							value="<?php if (isset($_POST['register']) || isset($_POST['update'])) echo $_POST['fathername']; ?>" />
					</td>
				</tr>
				<tr style="vertical-align: top">
					<td style="text-align: right" class="LabelColor" nowrap="nowrap">Last Name:</td>
					<td colspan="2" class="TitleColor">
						<input style="width:70%;height:30px;font-family:verdana;font-size:20px;" type="text" id="last" name="lastname"
							value="<?php if (isset($_POST['register']) || isset($_POST['update'])) echo $_POST['lastname']; ?>" />
					</td>
				</tr>
				<tr style="vertical-align: top">
					<td style="text-align: right" class="LabelColor">Pensioner Date of Death</td>
					<td colspan="2">
						<input type="date" id="dod" name="dod" style="width:70%;height:30;font-size:25px;"
							value="<?php if (isset($_POST['register']) || isset($_POST['update'])) echo $_POST['dod']; ?>" />
					</td>
				</tr>
				<tr style="vertical-align: top">
					<td style="text-align: right" class="LabelColor">Beneficiary Category</td>
					<td colspan="2">
						<select name="bencat" style="width:70%;height:30;font-family:verdana;font-size:20px;">
							<?php if (isset($_POST['register']) || isset($_POST['update'])) { ?>
								<option><?php echo $_POST['bencat']; ?></option>
							<?php } ?>
							<option>Spouse</option>
							<option>Child</option>
							<option>Father</option>
							<option>Mother</option>
						</select>
					</td>
				</tr>
				<tr style="vertical-align: top">
					<td style="text-align: right" class="LabelColor">Beneficiary Birth Date</td>
					<td colspan="2">
						<input type="date" id="dob" name="dob" style="width:70%;height:30;font-size:25px;"
							value="<?php if (isset($_POST['register']) || isset($_POST['update'])) echo $_POST['dob']; ?>" />
					</td>
				</tr>
				<tr style="vertical-align: top">
					<td style="text-align: right;font-size:25px;" class="LabelColor" nowrap="nowrap">
						<label for="share">Beneficiary Share in Percent</label>
					</td>
					<td colspan="2" class="TitleColor">
						<input name="share" type="number" style="width:70%;height:30;text-align:right;font-family:verdana;font-size:20px;" min="1" max="100"
							value="<?php if (isset($_POST['register']) || isset($_POST['update'])) echo $_POST['share']; ?>"><span>%</span>
					</td>
				</tr>
				<tr style="vertical-align: top" class="FooterColor">
					<td></td>
					<td colspan="3">
						<input style="width:30%;height:40;font-family:verdana;font-size:25px;" type="submit" name="register" value="Register" />
					</td>
				</tr>
			</table>
		</form>
		<?php
		$qdisp = "SELECT * FROM beneficiery WHERE fname='$fname'";
		$qdispr = $mysqli->query($qdisp) or die($mysqli->error);
		if ($qdispr->num_rows > 0) {
			// echo "<table border=1 style='width:100%;font-family:verdana;font-size:18px;'>";
			// echo "<tr><th>Pensioner</th><th>Category</th><th>Share</th></tr>"; // Removed Beneficiary column
			// while ($qdispf = $qdispr->fetch_assoc()) {
			// 	echo "<tr style='background-color:white;'>";
			// 	echo "<td>" . $qdispf['fname'] . "</td>";
			// 	echo "<td>" . $qdispf['bencat'] . "</td>";
			// 	echo "<td>" . $qdispf['benshare'] . "</td>";
			// 	echo "</tr>";
			// }
			// echo "</table>";
		}
		?>
	</div>
</body>

</html>