<?php
session_start();
include_once('connection.php');
error_reporting(1);
session_start();

if (!isset($_SESSION['user'])) {
  header("Location:login.php");
} else {
  $now = time();
  if ($now > $_SESSION['expire']) {
    session_destroy();
  }
}


$success = 0;
$fname = $_POST["fname"];
$sex = $_POST["sex"];
$bod = $_POST["dob"];
$type = $_POST["org"];
$nationality = $_POST["nationality"];
$mstatus = $_POST["mstatus"];
$salary = $_POST["salary"];
$service = $_POST["service"];
$rdate =  date("y-m-d");
$id = $_SESSION['sid'];

if (@$_REQUEST['register']) {
  if (empty($fname) or empty($sex) or empty($bod) or empty($type) or empty($nationality) or empty($mstatus) or empty($salary) or empty($service)) {
    $msg = "Fill the fields";
  } else if ((!preg_match('/[a-zA-Z]/', trim($_POST["fname"]))) or (!preg_match('/[a-zA-Z]/', trim($nationality))) or (!preg_match('/[a-zA-Z]/', trim($mstatus)))) {
    $msg = "Incorrect data. Please dont fill numbers in text fields.";
  } else {
    //   echo "here 1.....";
    $query = "select * from pensioner ORDER BY id DESC LIMIT 1";
    $query = $mysqli->query($query);
    //$t=$query->num_rows;	 	 	
    if ($query->num_rows >= 1) {
      $t = $query->fetch_assoc();

      if ($type == 'Public') {
        $orgid = "Pub" . $t['id'] + 1;
        $ssno = $orgid . $t['id'] + 3;
      } else {
        $orgid = "Pri" . $t['id'] + 1;
        $ssno = $orgid . $t['id'] + 3;
      }
    } else {
      if ($type == 'Public') {
        $orgid = "Pub" . "11";
        $ssno = $orgid . "13";
      } else {
        $orgid = "Pri" . "11";
        $ssno = $orgid . "13";
      }
    }
    // echo "here 3.....";					

    //$fp=addslashes(file_get_contents($_FILES['photo']['tmp_name'])); 
    $query = "INSERT INTO Pensioner(ssn,fname,sex,bod,orgtype,nationality,mstatus,salary,service,rdate,orgid,fp)  VALUES('$ssno', '$fname','$sex', '$bod','$type', '$nationality', '$mstatus',
					'$salary','$service', '$rdate','$orgid', '$fp')";
    $result = $mysqli->query($query) or die($mysqli->error);
    if ($result) {
      $msg = "You Have Successfully Registered a new User";
      $success = 1;
    } else {
      echo $ssno . $fname . $sex . $bod . $type . $nationality . $mstatus .
        $salary . $service . $rdate . $orgid . $fp;
      die('Error : (' . $con->errno . ') ' . $con->error);
    }
  }
}
?>
<html>

<head>
  <title>Pensioner Registration </title>
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
    <?php } else if ($roles['role'] == "Clerk") {   ?>
      <a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
      <a href="report.php" style="float:left;">Generate report</a><br>
      <a href="logout.php" style="float:left;">Logout</a><br>
    <?php } else if ($roles['role'] == "Organization") {   ?>
      <a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
      <a href="report.php" style="float:left;">Generate report</a><br>
      <a href="index.php" style="float:left;">Logout</a><br>
    <?php } ?>

  </div>
  <div style="float:right;width:70%;padding:10px;margin-top:50px;">
    <form method="post" enctype="multipart/form-data"
      style="background-color:white;width:100%;margin:auto;font-family:verdana;font-size:25px;border-radius:15px;">

      <table width="99%" border="0" cellpadding="8" cellspacing="10" style="font-family:verdana;font-size:23px;">
        <tr>
          <td colspan="3" class="HeaderColor">
            <h2 style="text-align:center;background-color:#D9D9D9;width:100%;"> New </h2>
            <hr color=blue>
            <font color="#FF0000" align=center>
              <?php
              echo $msg;
              if ($success == 1) {
                $q = "select * from Pensioner where ssn='$ssno' ";
                $qr = $mysqli->query($q);
                if ($qr->num_rows == 1) {
                  $qrf = $qr->fetch_assoc();

                  echo "<table border=1 style='border-collapse: collapse;text-align:center;'>";
                  echo "<tr>" . "<th>" . "SSN" . "</th>" . "<th>" . "Name" . "<th>" . "Sex" . "</th>" . "<th>" . "Org-ID" . "</th>" . "<th>" . "Birthdate" . "</th>" . "<th>" . "Type of org" . "</th>" . "<th>" . "Nationality" . "</th>" . "<th>" . "Marital status" . "</th>" . "<th>" . "Salary" . "</th>" . "<th>" . "Status" . "</th>" . "</tr>";
                  echo "<tr>" . "<td>" . $qrf['ssn'] . "</td>" . "<td>" . $qrf['fname'] . "</td>" . "<td>" . $qrf['sex'] . "</td>" . "<td>" . $qrf['orgid'] . "</td>" . "<td>" . $qrf['bod'] . "</td>" . "<td>" . $qrf['orgtype'] . "</td>" . "<td>" . $qrf['nationality'] . "</td>" . "<td>" . $qrf['mstatus'] . "</td>" . "<td>" . $qrf['salary'] . "</td>" . "<td>" . $qrf['service'] . "</td>" . "</tr>";
                  echo "</table>";
                }
              }

              ?></font>
          </td>
        </tr>
        <tr style="vertical-align: top">
          <td style="text-align: right" class="LabelColor" nowrap="nowrap">
            <label for="fname"> Full Name</label>
          </td>
          <td colspan="2" class="TitleColor">
            <input style="width:70%;height:30;" type="text" id="fname" name="fname" />
          </td>
        </tr>
        <tr style="vertical-align: top">
          <td style="text-align: right" class="LabelColor"> Gender </td>
          <td colspan="2">
            <label for="male">Male </label>
            <input type="radio" id="male" name="sex" value="M" />
            <label for="female">Female </label>
            <input type="radio" id="female" name="sex" value="F" />
          </td>
        </tr>
        <tr style="vertical-align: top">
          <td style="text-align: right" class="LabelColor"> Birth Date </td>
          <td colspan="2">
            <table border="0" cellspacing="2" cellpadding="0">
              <tr style="text-align: left">
                <td class="TitleColor">
                  <label for="day"> </label>
                  <input type="date" id="day" name="dob" min="1980-03-31" max="2002-04-01" style="width:70%;height:30;font-size:25px;" />
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr style="vertical-align: top">
          <td style="text-align: right" class="LabelColor" nowrap="nowrap">
            <label for="type"> Type of organization</label>
          </td>
          <td colspan="2" class="TitleColor">
            <select name="org" style="width:70%;height:30;font-size:25px;">
              <option>Public</option>
              <option>Private</option>
            </select>
          </td>
        </tr>
        <tr style="vertical-align: top">
          <td style="text-align: right" class="LabelColor" nowrap="nowrap">
            <label for="nationality"> Nationality</label>
          </td>
          <td colspan="2" class="TitleColor">
            <input style="width:70%;height:30;font-size:25px;" type="text" id="nationality" name="nationality" placeholder="Ethiopian" />
          </td>
        </tr>
        <tr style="vertical-align: top">
          <td style="text-align: right;font-size:25px;" class="LabelColor" nowrap="nowrap">
            <label for="mstatus"> Maritial Status</label>
          </td>
          <td colspan="2" class="TitleColor">
            <input style="width:70%;height:30;" type="text" id="mstatus" name="mstatus" />
          </td>
        </tr>
        <tr style="vertical-align: top">
          <td style="text-align: right;font-size:25px;" class="LabelColor" nowrap="nowrap">
            <label for="salary"> Last 3 years avg. salary</label>
          </td>
          <td colspan="2" class="TitleColor">
            <label>
              <input name="salary" type=number style="width:70%;height:30;" max=100000>
            </label>
          </td>
        </tr>
        <tr style="vertical-align: top">
          <td style="text-align: right;font-size:25px;" class="LabelColor" nowrap="nowrap">
            <label for="status"> Years of service</label>
          </td>
          <td colspan="2" class="TitleColor">
            <label>
              <input name="service" type=number style="width:70%;height:30;" max=50>
            </label>
          </td>
        </tr>
        </tr>
        <tr style="vertical-align: top" class="FooterColor">
          <td> </td>
          <td colspan="3">
            <input style="width:30%;height:40;font-family:verdana;font-size:25px;" type="submit" name="register" value="Register" />
            <label>
              <input style="width:30%;height:40;font-family:verdana;font-size:25px;" type="reset" name="Submit" value="Reset" />
            </label>
          </td>
        </tr>
      </table>
    </form>
    <?php
    ?>

  </div>
</body>

</html>