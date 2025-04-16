<?php
session_start();
include_once('connection.php');
error_reporting(1);

if (!isset($_SESSION['user'])) {
	header("Location:login.php");
} else {
	$now = time();
	if ($now > $_SESSION['expire']) {
		session_destroy();
	}
}
?>
<html>

<head>
	<title>PSSSA Official Page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/layout.css" rel="stylesheet" type="text/css" />
	<style>
		a:hover {
			color: black;
			background-color: white;
		}

		a {
			text-decoration: none;
		}

		th,
		td {
			padding: 8px;
			background-color: #f2f2f2;
			border-bottom: 1px solid blue;
			text-align: center;
		}

		tr:nth-child(even) {
			background-color: #e8ecef;
		}

		tr:hover {
			background-color: #d1e7ff;
		}

		.card {
			background-color: #ffffff;
			border-radius: 8px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			padding: 20px;
			margin: 10px;
			display: inline-block;
			text-align: center;
			min-width: 150px;
		}

		.card h3 {
			margin: 0 0 10px 0;
			font-family: verdana;
			font-size: 18px;
		}

		.card p {
			margin: 0;
			font-family: verdana;
			font-size: 24px;
			font-weight: bold;
		}

		.error-message {
			background-color: #ffe6e6;
			border: 1px solid #cc0000;
			padding: 10px;
			border-radius: 5px;
			color: #cc0000;
			margin-bottom: 20px;
			text-align: center;
		}

		.pagination {
			margin: 20px 0;
			text-align: center;
		}

		.pagination a {
			padding: 8px 12px;
			margin: 0 5px;
			border: 1px solid #007bff;
			border-radius: 5px;
			color: #007bff;
			text-decoration: none;
		}

		.pagination a:hover {
			background-color: #007bff;
			color: white;
		}

		.pagination a.active {
			background-color: #007bff;
			color: white;
		}

		.loader {
			border: 4px solid #f3f3f3;
			border-top: 4px solid #3498db;
			border-radius: 50%;
			width: 24px;
			height: 24px;
			animation: spin 1s linear infinite;
			display: none;
			margin: 10px auto;
		}

		@keyframes spin {
			0% {
				transform: rotate(0deg);
			}

			100% {
				transform: rotate(360deg);
			}
		}

		.detail-button {
			padding: 6px 12px;
			background-color: #007bff;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 14px;
		}

		.detail-button:hover {
			background-color: #0056b3;
		}

		.modal {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			justify-content: center;
			align-items: center;
			z-index: 1000;
		}

		.modal-content {
			background-color: white;
			padding: 20px;
			border-radius: 8px;
			width: 90%;
			max-width: 600px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
			position: relative;
		}

		.modal-content h3 {
			margin: 0 0 15px;
			font-family: verdana;
			font-size: 20px;
			text-align: center;
		}

		.modal-content p {
			margin: 10px 0;
			font-family: verdana;
			font-size: 16px;
		}

		.close-button {
			position: absolute;
			top: 10px;
			right: 15px;
			font-size: 24px;
			cursor: pointer;
			color: #333;
		}
	</style>
</head>

<body bgcolor="lightblue">
	<div style="width:100%;background-color:gray;margin-bottom:-20px;">
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
	<div style="padding:20px;width:20%;margin:auto;background-color:E1F8DC;text-align:center;font-family:verdana;font-size:18px;
             margin-top:50px; border-radius:0px;float:left;line-height:50px;border:solid blue 1px;height:100%;">
		<?php if ($roles['role'] == "Admin") { ?>
			<a href="register_company.php" style="float:left;">Register new company</a> <br>
			<a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
			<a href="choose_company_2.php" style="float:left;">Register beneficiery</a> <br>
			<a href="create.php" style="float:left;">Add new user</a><br>
			<a href="report.php" style="float:left;">Generate report</a><br>
			<a href="viewfeed.php" style="float:left;">View feedbacks</a><br>
			<a href="calculate.php" style="float:left;">Calculate Pension</a><br>
			<a href="logout.php" style="float:left;">Logout</a><br>
		<?php } else if ($roles['role'] == "Pensioner") { ?>
			<a href="feedback.php" style="float:left;">Send feedback</a> <br>
			<a href="report.php" style="float:left;">Generate report</a><br>
			<a href="logout.php" style="float:left;">Logout</a><br>
		<?php } else if ($roles['role'] == "Clerk") { ?>
			<a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
			<a href="report.php" style="float:left;">Generate report</a><br>
			<a href="logout.php" style="float:left;">Logout</a><br>
		<?php } else if ($roles['role'] == "Organization") { ?>
			<a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
			<a href="report.php" style="float:left;">Generate report</a><br>
			<a href="index.php" style="float:left;">Logout</a><br>
		<?php } ?>
	</div>
	<div style="background-color:;text-indent:0px;margin-top:50px;width:70%;font-family:verdana;font-size:25px;float:right;line-height:40px;background-color:white;border-radius:10px;padding:25px;height:90%;padding-bottom:500px;">
		<?php
		if (!isset($_SESSION['pension_choice']) || !in_array($_SESSION['pension_choice'], ['public', 'private'])) {
			echo "<div class='error-message'>Please select a pension type (public or private) from the <a href='pension_choice.php'>Pension Choice</a> page.</div>";
		} else {
			$pension_choice = $_SESSION['pension_choice'];
		?>
			<form method="POST" style="font-family:verdana;font-size:18px;" onsubmit="document.getElementById('loader').style.display='block';">
				<label for="ssnumber">Enter SSN</label>
				<input type="text" name="ssnumber" id="ssnumber" required>
				<input type="submit" name="search" value="Search">
				<div id="loader" class="loader"></div>
			</form>
			<hr>
			<?php
			if (isset($_POST['search'])) {
				$id = $_POST['ssnumber'];
				if (empty($id)) {
					echo "<div class='error-message'>Please enter an SSN.</div>";
				} else {
					$searchsql = "SELECT p.*, b.id AS ben_id, b.first AS ben_first, b.father AS ben_father, b.last AS ben_last, 
                              b.deathdate AS ben_deathdate, b.bencat AS ben_bencat, b.benbirth AS ben_benbirth, b.benshare AS ben_benshare 
                              FROM pensioner p 
                              LEFT JOIN beneficiery b ON p.fname = b.fname 
                              WHERE p.ssn = ? AND p.orgtype = ?";
					$stmt = $mysqli->prepare($searchsql);
					$stmt->bind_param("ss", $id, $pension_choice);
					$stmt->execute();
					$runsql = $stmt->get_result();
					if ($runsql->num_rows > 0) {
						$fetchsql = $runsql->fetch_assoc();
						$birth_date = new DateTime($fetchsql['bod']);
						$current_date = new DateTime();
						$retirement_date = clone $birth_date;
						$retirement_date->modify('+60 years');
						if ($current_date >= $retirement_date) {
							$time_left = 'Retired';
						} else {
							$interval = $current_date->diff($retirement_date);
							$time_left = $interval->y . ' years, ' . $interval->m . ' months';
						}
						echo "<table style='width:100%;margin-bottom:50px;font-size:18px;'>";
						echo "<caption style='font-family:verdana;font-size:22px;'>Pensioner Information</caption>";
						echo "<tr>";
						echo "<th>Photo</th><th>SSN</th><th>Name</th><th>Birth Date</th><th>Organization</th><th>Nationality</th><th>Marital Status</th><th>Sex</th><th>Registration Date</th><th>beneficiery Detail</th><th>Time Left for Retirement</th>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><img width=100 height=100 src='" . htmlspecialchars($fetchsql['photo']) . "'></td>";
						echo "<td>" . htmlspecialchars($fetchsql['ssn']) . "</td>";
						echo "<td>" . htmlspecialchars($fetchsql['fname']) . "</td>";
						echo "<td>" . htmlspecialchars($fetchsql['bod']) . "</td>";
						echo "<td>" . htmlspecialchars($fetchsql['orgtype']) . "</td>";
						echo "<td>" . htmlspecialchars($fetchsql['nationality']) . "</td>";
						echo "<td>" . htmlspecialchars($fetchsql['mstatus']) . "</td>";
						echo "<td>" . htmlspecialchars($fetchsql['sex']) . "</td>";
						echo "<td>" . htmlspecialchars($fetchsql['rdate']) . "</td>";
						echo "<td><button class='detail-button' onclick='showbeneficieryDetail(" .
							json_encode($fetchsql['ben_id'] ?? '') . ", " .
							json_encode($fetchsql['ben_first'] ?? '') . ", " .
							json_encode($fetchsql['ben_father'] ?? '') . ", " .
							json_encode($fetchsql['ben_last'] ?? '') . ", " .
							json_encode($fetchsql['ben_deathdate'] ?? '') . ", " .
							json_encode($fetchsql['ben_bencat'] ?? '') . ", " .
							json_encode($fetchsql['ben_benbirth'] ?? '') . ", " .
							json_encode($fetchsql['ben_benshare'] ?? '') . ")'>Show beneficiery Detail</button></td>";
						echo "<td>" . htmlspecialchars($time_left) . "</td>";
						echo "</tr>";
						echo "</table>";
					} else {
						echo "<div class='error-message'>No pensioner found with SSN: " . htmlspecialchars($id) . " for $pension_choice pension type. Please try another SSN.</div>";
					}
					$stmt->close();
				}
			}
			?>
			<hr>
			<div style="text-align:center;">
				<h2 style="font-family:verdana;font-size:22px;">Statistics of Registered Pensioners (<?php echo htmlspecialchars(ucfirst($pension_choice)); ?>)</h2>
				<div class="card">
					<h3>Male</h3>
					<p>
						<?php
						$sq = "SELECT COUNT(*) as count FROM pensioner WHERE sex = 'M' AND orgtype = ?";
						$stmt = $mysqli->prepare($sq);
						$stmt->bind_param("s", $pension_choice);
						$stmt->execute();
						$rsq = $stmt->get_result();
						echo $rsq->fetch_assoc()['count'];
						$stmt->close();
						?>
					</p>
				</div>
				<div class="card">
					<h3>Female</h3>
					<p>
						<?php
						$sq = "SELECT COUNT(*) as count FROM pensioner WHERE sex = 'F' AND orgtype = ?";
						$stmt = $mysqli->prepare($sq);
						$stmt->bind_param("s", $pension_choice);
						$stmt->execute();
						$rsq = $stmt->get_result();
						echo $rsq->fetch_assoc()['count'];
						$stmt->close();
						?>
					</p>
				</div>
				<div class="card">
					<h3>Total</h3>
					<p>
						<?php
						$sq = "SELECT COUNT(*) as count FROM pensioner WHERE orgtype = ?";
						$stmt = $mysqli->prepare($sq);
						$stmt->bind_param("s", $pension_choice);
						$stmt->execute();
						$rsq = $stmt->get_result();
						echo $rsq->fetch_assoc()['count'];
						$stmt->close();
						?>
					</p>
				</div>
				<div style="font-size:16px;">
					<?php
					$sqyear = "SELECT DISTINCT rdate FROM pensioner WHERE orgtype = ?";
					$stmt = $mysqli->prepare($sqyear);
					$stmt->bind_param("s", $pension_choice);
					$stmt->execute();
					$sqyearun = $stmt->get_result();
					if ($sqyearun->num_rows > 0) {
						while ($sqyearf = $sqyearun->fetch_assoc()) {
							$yr = $sqyearf['rdate'];
							$sqq = "SELECT COUNT(*) as count FROM pensioner WHERE rdate = ? AND sex = 'M' AND orgtype = ?";
							$stmt_m = $mysqli->prepare($sqq);
							$stmt_m->bind_param("ss", $yr, $pension_choice);
							$stmt_m->execute();
							$m = $stmt_m->get_result()->fetch_assoc()['count'];
							$stmt_m->close();
							$sqq = "SELECT COUNT(*) as count FROM pensioner WHERE rdate = ? AND sex = 'F' AND orgtype = ?";
							$stmt_f = $mysqli->prepare($sqq);
							$stmt_f->bind_param("ss", $yr, $pension_choice);
							$stmt_f->execute();
							$f = $stmt_f->get_result()->fetch_assoc()['count'];
							$stmt_f->close();
							echo "<div>";
							echo "<h3 style='font-family:verdana;font-size:18px;text-align:left;'>Date: " . htmlspecialchars($sqyearf['rdate']) . "</h3>";
						}
					}
					$stmt->close();
					?>
				</div>
			</div>
			<hr>
			<div>
				<h2 style="font-family:verdana;font-size:22px;">All Registered Pensioners (<?php echo htmlspecialchars(ucfirst($pension_choice)); ?>)</h2>
				<?php
				$records_per_page = 10;
				$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
				$offset = ($page - 1) * $records_per_page;
				$total_sql = "SELECT COUNT(*) as count FROM pensioner WHERE orgtype = ?";
				$stmt = $mysqli->prepare($total_sql);
				$stmt->bind_param("s", $pension_choice);
				$stmt->execute();
				$total_result = $stmt->get_result();
				$total_rows = $total_result->fetch_assoc()['count'];
				$stmt->close();
				$total_pages = ceil($total_rows / $records_per_page);
				$all_pensioners_sql = "SELECT p.*, b.id AS ben_id, b.first AS ben_first, b.father AS ben_father, b.last AS ben_last, 
                                   b.deathdate AS ben_deathdate, b.bencat AS ben_bencat, b.benbirth AS ben_benbirth, b.benshare AS ben_benshare 
                                   FROM pensioner p 
                                   LEFT JOIN beneficiery b ON p.fname = b.fname 
                                   WHERE p.orgtype = ? 
                                   LIMIT ? OFFSET ?";
				$stmt = $mysqli->prepare($all_pensioners_sql);
				$stmt->bind_param("sii", $pension_choice, $records_per_page, $offset);
				$stmt->execute();
				$all_pensioners_result = $stmt->get_result();
				?>
				<table style="width:100%;margin-bottom:50px;font-size:18px;">
					<tr>
						<th>Photo</th>
						<th>SSN</th>
						<th>Name</th>
						<th>Birth Date</th>
						<th>Organization</th>
						<th>Nationality</th>
						<th>Marital Status</th>
						<th>Sex</th>
						<th>Registration Date</th>
						<th>beneficiery Detail</th>
						<th>Time Left for Retirement</th>
					</tr>
					<?php
					if ($all_pensioners_result->num_rows > 0) {
						while ($pensioner = $all_pensioners_result->fetch_assoc()) {
							$birth_date = new DateTime($pensioner['bod']);
							$current_date = new DateTime();
							$retirement_date = clone $birth_date;
							$retirement_date->modify('+60 years');
							if ($current_date >= $retirement_date) {
								$time_left = 'Retired';
							} else {
								$interval = $current_date->diff($retirement_date);
								$time_left = $interval->y . ' years, ' . $interval->m . ' months';
							}
							echo "<tr>";
							echo "<td><img width=100 height=100 src='" . htmlspecialchars($pensioner['photo']) . "'></td>";
							echo "<td>" . htmlspecialchars($pensioner['ssn']) . "</td>";
							echo "<td>" . htmlspecialchars($pensioner['fname']) . "</td>";
							echo "<td>" . htmlspecialchars($pensioner['bod']) . "</td>";
							echo "<td>" . htmlspecialchars($pensioner['orgtype']) . "</td>";
							echo "<td>" . htmlspecialchars($pensioner['nationality']) . "</td>";
							echo "<td>" . htmlspecialchars($pensioner['mstatus']) . "</td>";
							echo "<td>" . htmlspecialchars($pensioner['sex']) . "</td>";
							echo "<td>" . htmlspecialchars($pensioner['rdate']) . "</td>";
							echo "<td><button class='detail-button' onclick='showbeneficieryDetail(" .
								json_encode($pensioner['ben_id'] ?? '') . ", " .
								json_encode($pensioner['ben_first'] ?? '') . ", " .
								json_encode($pensioner['ben_father'] ?? '') . ", " .
								json_encode($pensioner['ben_last'] ?? '') . ", " .
								json_encode($pensioner['ben_deathdate'] ?? '') . ", " .
								json_encode($pensioner['ben_bencat'] ?? '') . ", " .
								json_encode($pensioner['ben_benbirth'] ?? '') . ", " .
								json_encode($pensioner['ben_benshare'] ?? '') . ")'>Show beneficiery Detail</button></td>";
							echo "<td>" . htmlspecialchars($time_left) . "</td>";
							echo "</tr>";
						}
					} else {
						echo "<tr><td colspan='11'>No pensioners found for $pension_choice pension type.</td></tr>";
					}
					$stmt->close();
					?>
				</table>
				
			</div>
		<?php } ?>
		<div id="beneficieryModal" class="modal">
			<div class="modal-content">
				<span class="close-button" onclick="closeModal()">×</span>
				<h3>beneficiery Details</h3>
				<div id="beneficieryDetails"></div>
			</div>
		</div>
	</div>
	<script>
		function showbeneficieryDetail(id, first, father, last, deathdate, bencat, benbirth, benshare) {
			const modal = document.getElementById('beneficieryModal');
			const details = document.getElementById('beneficieryDetails');
			if (id || first || father || last || deathdate || bencat || benbirth || benshare) {
				details.innerHTML = `
					<p><strong>ID:</strong> ${id || '-'}</p>
					<p><strong>First Name:</strong> ${first || '-'}</p>
					<p><strong>Father's Name:</strong> ${father || '-'}</p>
					<p><strong>Last Name:</strong> ${last || '-'}</p>
					<p><strong>Pensioner Death Date:</strong> ${deathdate || '-'}</p>
					<p><strong>beneficiery Category:</strong> ${bencat || '-'}</p>
					<p><strong>beneficiery Birth Date:</strong> ${benbirth || '-'}</p>
					<p><strong>beneficiery Share (%):</strong> ${benshare || '-'}</p>
				`;
			} else {
				details.innerHTML = '<p>No beneficiery found.</p>';
			}
			modal.style.display = 'flex';
		}

		function closeModal() {
			const modal = document.getElementById('beneficieryModal');
			modal.style.display = 'none';
		}

		window.onclick = function(event) {
			const modal = document.getElementById('beneficieryModal');
			if (event.target === modal) {
				closeModal();
			}
		};
	</script>
</body>

</html>