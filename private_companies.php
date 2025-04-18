<?php
session_start();
include 'connection.php';
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
    </style>
</head>

<body bgcolor="lightblue">
    <div style="padding:10px;width:99%;margin:auto;">
        <div style="width:100%;background-color:gray;margin-bottom:-20px;">
            <img src="pssa.jpg" style="width:100%;">
        </div>
        <?php
        $user = $_SESSION['user'];
        $sql = "select role from users where username='$user'";
        $result = $mysqli->query($sql);
        $roles = $result->fetch_assoc();
        ?>
        <div style="padding:20px;width:20%;margin:auto;background-color:E1F8DC;text-align:center;font-family:verdana;font-size:18px;
             margin-top:50px; border-radius:0px;float:left;line-height:50px;border:solid blue 1px;height:100%;">
            <?php if ($roles['role'] == "Admin") { ?>
                <a href="register_company.php" style="float:left;">Register new company</a> <br>

                <a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
                <a href="choose_company_2.php" style="float:left;">Register Beneficiery</a> <br>
                <a href="create.php" style="float:left;">Add new user</a><br>
                <a href="report.php" style="float:left;">Generate report</a><br>
                <a href="viewfeed.php" style="float:left;">View feedbacks</a><br>
                <a href="calculate.php" style="float:left;">Calculate Pension</a><br>
                <a href="logout.php" style="float:left;">Logout</a><br>
            <?php } else if ($roles['role'] == "Pensioner") { ?>
                <a href="feedback.php" style="float:left;">Send feedback</a> <br>
                <a href="report.php" style="float:left;">Generate report</a><br>
                <a href="logout.php" style="float:left;">Logout</a><br>
            <?php } else if ($roles['role'] == "Clerk") {     ?>
                <a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
                <a href="report.php" style="float:left;">Generate report</a><br>
                <a href="logout.php" style="float:left;">Logout</a><br>
            <?php } else if ($roles['role'] == "Organization") {     ?>
                <a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
                <a href="report.php" style="float:left;">Generate report</a><br>
                <a href="index.php" style="float:left;">Logout</a><br>
            <?php } ?>

        </div>
    </div>
    <div style="background-color:;text-indent:0px;margin-top:50px;width:70%;font-family:verdana;font-size:25px;float:right;line-height:40px;background-color:white;border-radius:10px;padding:25px;height:100%;">

        The Private Companies Pension Administration is a regulatory body established to manage and oversee pension schemes for employees in the private sector. Its primary goal is to ensure a sustainable and inclusive pension system that benefits both employees and employers. The administration is responsible for registering and issuing pensions, collecting contributions from employers and employees, determining pension eligibility and benefits, ensuring timely and accurate pension payments, and administering pension funds with transparency and integrity. By undertaking these roles, the administration aims to enhance financial security and welfare for private sector employees during their retirement years.<br>



    </div>
</body>

</html>