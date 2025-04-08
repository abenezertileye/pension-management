<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit;
}

// Include database connection
include 'connection.php';

// Fetch user role
$user = $_SESSION['user'];
$sql = "SELECT role FROM users WHERE username = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $roles = $result->fetch_assoc();
    $stmt->close();
} else {
    $roles['role'] = "Guest";
}

// Get the organization type from the session (set in pension_choice.php)
if (!isset($_SESSION['pension_choice']) || !in_array($_SESSION['pension_choice'], ['public', 'private'])) {
    header("location: pension_choice.php"); // Redirect if no valid choice
    exit;
}
$org_type = $_SESSION['pension_choice'];

// Fetch companies based on organization type
$sql = "SELECT id, company_name FROM companies WHERE organization_type = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $org_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $companies = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $companies = [];
}

// Handle company selection
if (isset($_POST['submit'])) {
    $selected_company_id = $_POST['company_id'];
    $_SESSION['selected_company_id'] = $selected_company_id;

    // Determine the next page based on the referrer or context
    // We'll use a hidden field or session to track this
    $next_page = 'pensioner.php'; // Default to pensioner registration
    header("location: pensioner.php");
    exit;
}
?>

<html>

<head>
    <title>Choose Company</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            background-color: lightblue;
            font-family: Verdana, sans-serif;
            margin: 0;
        }

        a:hover {
            color: black;
            background-color: white;
        }

        a {
            text-decoration: none;
            color: blue;
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
            font-family: Verdana, sans-serif;
            font-size: 18px;
            margin-top: 50px;
            margin-left: 15px;
            float: left;
            line-height: 50px;
            border: solid blue 1px;
            min-height: calc(100vh - 70px);
            box-sizing: border-box;
        }

        .sidebar a {
            display: block;
            text-align: left;
        }

        .content {
            float: right;
            width: 70%;
            padding: 10px;
            margin-top: 50px;
            margin-right: 50px;
        }

        .form-container {
            background-color: white;
            width: 100%;
            margin: auto;
            font-family: Verdana, sans-serif;
            font-size: 25px;
            border-radius: 15px;
            padding: 20px;
        }

        table {
            width: 99%;
            border: 0;
            cellpadding: 8px;
            cellspacing: 10px;
            font-family: Verdana, sans-serif;
            font-size: 23px;
        }

        .HeaderColor h2 {
            text-align: center;
            background-color: #D9D9D9;
            width: 100%;
            margin: 0;
            padding: 10px 0;
        }

        .LabelColor {
            text-align: right;
            vertical-align: top;
        }

        .TitleColor select {
            width: 70%;
            height: 30px;
            font-size: 25px;
        }

        input[type="submit"] {
            width: 30%;
            height: 40px;
            font-family: Verdana, sans-serif;
            font-size: 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="pssa.jpg" alt="PSSA Logo">
    </div>

    <div class="sidebar">
        <?php if ($roles['role'] == "Admin") { ?>
            <a href="register_company.php">Register new company</a>
            <a href="choose_company.php">Register new pensioner</a>
            <a href="choose_company.php">Register Beneficiery</a>
            <a href="create.php">Add new user</a>
            <a href="report.php">Generate report</a>
            <a href="viewfeed.php">View feedbacks</a>
            <a href="calculate.php">Calculate Pension</a>
            <a href="logout.php">Logout</a>
        <?php } else if ($roles['role'] == "Clerk" || $roles['role'] == "Organization") { ?>
            <a href="choose_company.php">Register new pensioner</a>
            <a href="report.php">Generate report</a>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <p>No menu options available</p>
        <?php } ?>
    </div>

    <div class="content">
        <form method="post" class="form-container">
            <table>
                <tr>
                    <td colspan="3" class="HeaderColor">
                        <h2>Choose a <?php echo ucfirst($org_type); ?> Company</h2>
                        <hr color="blue">
                    </td>
                </tr>
                <tr style="vertical-align: top">
                    <td style="text-align: right" class="LabelColor" nowrap="nowrap">
                        <label for="company_id">Select Company</label>
                    </td>
                    <td colspan="2" class="TitleColor">
                        <select id="company_id" name="company_id" required>
                            <option value="">-- Select a Company --</option>
                            <?php foreach ($companies as $company) { ?>
                                <option value="<?php echo $company['id']; ?>">
                                    <?php echo htmlspecialchars($company['company_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <?php if (empty($companies)) { ?>
                            <span class="error">No <?php echo $org_type; ?> companies available. Please register one first.</span>
                        <?php } ?>
                    </td>
                </tr>
                <tr style="vertical-align: top">
                    <td></td>
                    <td colspan="2">
                        <input type="hidden" name="next_page" value="<?php echo isset($_GET['next']) ? htmlspecialchars($_GET['next']) : 'pensioner.php'; ?>">
                        <input type="submit" name="submit" value="Proceed">
                    </td>
                </tr>
            </table>
        </form>
        <p style="text-align: center; margin-top: 20px;">
            <a href="<?php echo $org_type; ?>_companies.php">Back</a> |
            <a href="logout.php">Logout</a>
        </p>
    </div>
</body>

</html>