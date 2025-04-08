<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit;
}

// Include database connection
include 'connection.php';  // Assuming this is your database connection file

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
    $roles['role'] = "Guest";  // Default role if query fails
}

// Initialize variables
$company_name = $organization_type = "";
$name_err = $type_err = $msg = "";

// Process form submission
if (isset($_POST['submit'])) {
    // Validate company name
    if (empty(trim($_POST['company_name']))) {
        $name_err = "Please enter a company name";
    } else {
        $company_name = trim($_POST['company_name']);
    }

    // Validate organization type
    if (empty(trim($_POST['organization_type']))) {
        $type_err = "Please select an organization type";
    } else {
        $organization_type = trim($_POST['organization_type']);
    }

    // If no errors, insert into database
    if (empty($name_err) && empty($type_err)) {
        $sql = "INSERT INTO companies (company_name, organization_type) VALUES (?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ss", $company_name, $organization_type);
            if ($stmt->execute()) {
                $msg = "Company registered successfully!";
                $company_name = $organization_type = ""; // Clear form
            } else {
                $msg = "Error registering company. Please try again.";
            }
            $stmt->close();
        } else {
            $msg = "Database error. Please try again.";
        }
    }
}
?>

<html>

<head>
    <title>Register New Company</title>
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

        .TitleColor input,
        .TitleColor select {
            width: 70%;
            height: 30px;
            font-size: 25px;
        }

        .error {
            color: red;
            font-size: 16px;
        }

        .success {
            color: green;
            font-size: 16px;
        }

        input[type="submit"],
        input[type="reset"] {
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

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body style="margin: 10px;">
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
        <?php } else if ($roles['role'] == "Pensioner") { ?>
            <a href="feedback.php">Send feedback</a>
            <a href="report.php">Generate report</a>
            <a href="logout.php">Logout</a>
        <?php } else if ($roles['role'] == "Clerk") { ?>
            <a href="choose_company.php">Register new pensioner</a>
            <a href="report.php">Generate report</a>
            <a href="logout.php">Logout</a>
        <?php } else if ($roles['role'] == "Organization") { ?>
            <a href="choose_company.php">Register new pensioner</a>
            <a href="report.php">Generate report</a>
            <a href="index.php">Logout</a>
        <?php } else { ?>
            <p>No menu options available</p>
        <?php } ?>
    </div>

    <div class="content">
        <form method="post" class="form-container">
            <table>
                <tr>
                    <td colspan="3" class="HeaderColor">
                        <h2>Register New Company</h2>
                        <hr color="blue">
                        <font color="#FF0000" align="center">
                            <?php
                            if (!empty($msg)) {
                                echo "<p class='" . (strpos($msg, 'successfully') !== false ? 'success' : 'error') . "'>";
                                echo $msg;
                                echo "</p>";
                            }
                            ?>
                        </font>
                    </td>
                </tr>
                <tr style="vertical-align: top">
                    <td style="text-align: right" class="LabelColor" nowrap="nowrap">
                        <label for="company_name">Company Name</label>
                    </td>
                    <td colspan="2" class="TitleColor">
                        <input type="text" id="company_name" name="company_name"
                            value="<?php echo htmlspecialchars($company_name); ?>" required>
                        <span class="error"><?php echo $name_err; ?></span>
                    </td>
                </tr>
                <tr style="vertical-align: top">
                    <td style="text-align: right" class="LabelColor" nowrap="nowrap">
                        <label for="organization_type">Organization Type</label>
                    </td>
                    <td colspan="2" class="TitleColor">
                        <select id="organization_type" name="organization_type">
                            <option value="">Select Type</option>
                            <option value="public" <?php echo ($organization_type == "public") ? "selected" : ""; ?>>Public</option>
                            <option value="private" <?php echo ($organization_type == "private") ? "selected" : ""; ?>>Private</option>
                        </select>
                        <span class="error"><?php echo $type_err; ?></span>
                    </td>
                </tr>
                <tr style="vertical-align: top">
                    <td></td>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Register">
                        <input type="reset" name="reset" value="Reset">
                    </td>
                </tr>
            </table>
        </form>
        <p style="text-align: center; margin-top: 20px;">
            <a href="pension_choice.php">Back to Pension Choice</a> |
            <a href="logout.php">Logout</a>
        </p>
    </div>
</body>

</html>