<?php 
session_start(); include_once('connection.php'); error_reporting(1); 
session_start();

if(!isset($_SESSION['user'])){
						header("Location:login.php");
					}else{
						$now = time();
					if ($now > $_SESSION['expire']) {
						session_destroy(); 
					}
					}

$orgid = $_POST["orgid"]; 
$ssno = $_POST["ssno"]; 
$fname = $_POST["fname"]; 
$sex = $_POST["sex"]; 
$dd = $_POST["dd"]; 
$mm = $_POST["mm"]; 
$yyyy = $_POST["yyyy"]; 
$title = $_POST["title"]; 
$status = $_POST["status"]; 
$mstatus = $_POST["mstatus"]; 
$rdate =  date("y-m-d"); 
$nationality = $_POST["nationality"]; 
$bod = "$yyyy-$mm-$dd"; 
$id=$_SESSION['sid']; 
 
if(@$_REQUEST['register']) 
{ 
  if($orgid==" " or $ssno==" " or $fname==""  or $dd==" " or $mm==" " or $yyyy==" " or $title==" " or $status==" "    or $mstatus==" " or $nationality==" ") 
	 	 	 	{ 
	 	 	 	$msg="Fill the fields"; 
	 	 	 	} 
	 	 	else 
	 	 	 	{ 
			   echo "here 1.....";
	 	 	 	$query ="SELECT * FROM pensioner where ssn='$ssno'"; 
				$query=$mysqli->query($query);
				$t=$query->num_rows;	 	 	
			 if($t>=1) 
	 	 	 	{ 
			  //  echo "here 2.....";
	 	 	 	$msg="The pensioner/SSID already exists"; 
	 	 	 	} 
	 	 	 	else  
	 	 	 	{
              // echo "here 3.....";					
	 	 	 	 	
	 	 	 	 	//$fp=addslashes(file_get_contents($_FILES['photo']['tmp_name'])); 
	 	 	 	 	$query = "INSERT INTO Pensioner  VALUES('$ssno', '$fname','$sex', '$bod', '$nationality', '$mstatus',
					'$title','$status', '$rdate','$orgid', '$id','$fp')"; 
	 	 	 	 	$result = $mysqli->query($query); 
	 	 	 	 	 	if($result) 
	 	 	 	 	 	{ 
	 	 	 	 	 	 $msg="You Have Successfully Registered a new User" ; 
	 	 	 	 	 	} 
	 	 	 	 	 	else 
	 	 	 	 	 	{ 
	 	 	 	 	 	die('Error : ('. $con->errno .') '. $con->error); 
	 	 	 	 	 	} 
	 	 	 	 	 
	 	 	 	} 
	 	 	} 
} 
?> 
<html>
<head>
 <title>Pensioner Registration </title>
 <style> 
  a:hover{
	  color:black;
	  background-color:white;
  } 
  a{
  
  text-decoration : none;
}
</style> 
</head> 
<body bgcolor="lightblue">
<div style="width:100%;background-color:gray;margin-bottom:10px;">
   <img src="pssa.jpg" style="width:100%;">
</div>
 <?php 
       $user=$_SESSION['user'];
	   $sql="select role from users where username='$user'";
       $result=$mysqli->query($sql);
	   $roles=$result->fetch_assoc();
 ?>
  <div  style="padding:20px;width:20%;margin:auto;background-color:E1F8DC;text-align:center;font-family:verdana;font-size:18px;
             margin-top:50px; border-radius:0px;float:left;line-height:50px;border:solid blue 1px;height:100%;">
   <?php if($roles['role']=="Admin"){ ?> 
   <a href="pensioner.php" style="float:left;">Register </a> <br>
   <a href="create.php" style="float:left;">Add new user</a><br>
   <a href="report.php" style="float:left;">Generate report</a><br>
   <a href="viewfeed.php" style="float:left;">View feedbacks</a><br>
   <a href="logout.php" style="float:left;">Logout</a><br>
   <?php }
     else if($roles['role']=="Pensioner"){ ?>
   <a href="feedback.php" style="float:left;">Send feedback</a> <br>
   <a href="report.php" style="float:left;">Generate report</a><br>
   <a href="logout.php" style="float:left;">Logout</a><br>
	 <?php } 
	 else if($roles['role']=="Clerk"){	 ?>   
   <a href="pensioner.php" style="float:left;">Register new pensioner</a> <br>
   <a href="report.php" style="float:left;">Generate report</a><br>
   <a href="logout.php" style="float:left;">Logout</a><br>
     <?php } 
	  else if($roles['role']=="Organization"){	 ?>  
   <a href="pensioner.php" style="float:left;">Register new pensioner</a> <br>
   <a href="report.php" style="float:left;">Generate report</a><br>
   <a href="index.php" style="float:left;">Logout</a><br>  
	  <?php } ?>

</div>
<div style="float:right;width:70%;padding:10px;margin-top:50px;">
    <form  method="post" enctype="multipart/form-data" 
	        style="background-color:white;width:100%;margin:auto;font-family:verdana;font-size:25px;border-radius:15px;"> 
     
  <table width="99%" border="0" cellpadding="8" cellspacing="10" style="font-family:verdana;font-size:23px;"> 
    <tr> 
      <td colspan="3" class="HeaderColor">        
		<h2 style="text-align:center;">Register New Pensioner </h2> 
        <hr color=blue>	
          <font color="#FF0000" align=center> <?php echo $msg;?></font>		
	  </td>     
	</tr> 
	 	<tr style="vertical-align: top"> 
      <td width="44%" nowrap="nowrap" class="LabelColor" style="text-align: right"> 
        <label for="orgid"> Organaization ID</label>      </td> 
      <td width="56%" colspan="2" class="TitleColor"><label> 
      <input style="width:70%;height:30;" type="text" id="orgid" name="orgid" /> 
      </label></td> 
    </tr> 
     <tr style="vertical-align: top"> 
      <td width="44%" nowrap="nowrap" class="LabelColor" style="text-align: right"> 
        <label for="ssno"> SSNO</label>      </td> 
      <td colspan="2" class="TitleColor"> 
        <input  style="width:70%;height:30;" type="text" id="ssno" name="ssno" /> 	         </td>     </tr> 
        <tr style="vertical-align: top"> 
  <td style="text-align: right" class="LabelColor"> 
    <label for="fname"> First Name</label>
  </td> 
  <td colspan="2" class="TitleColor"> 
    <input style="width:70%;height:30;" type="text" id="fname" name="fname" onkeypress="return /[a-zA-Z ]/i.test(event.key)" /> 
  </td>     
</tr>

<tr style="vertical-align: top"> 
  <td style="text-align: right" class="LabelColor"> 
    <label for="lname"> Last Name</label>
  </td> 
  <td colspan="2" class="TitleColor"> 
    <input style="width:70%;height:30;" type="text" id="lname" name="lname" onkeypress="return /[a-zA-Z ]/i.test(event.key)" /> 
  </td>     
</tr>

    <tr style="vertical-align: top">
    <td style="text-align: right" class="LabelColor"> Birth Date </td>
    <td colspan="2">
        <table border="0" cellspacing="2" cellpadding="0">
            <tr style="text-align: left">
                <td class="TitleColor">
                    <label for="day">DD </label>
                    <input type="text" id="day" name="dd" size="2" />
                </td>
                <td class="TitleColor">
                    <label for="month">MM </label>
                    <input type="text" id="month" name="mm" size="2" />
                </td>
                <td class="TitleColor">
                    <label for="year">YYYY </label>
                    <input type="text" id="year" name="yyyy" size="4" />
                </td>
            </tr>
        </table>
    </td>
</tr>

<script>
    // Get the year input field
    const yearInput = document.getElementById('year');
    
    // Restrict input to only years before 2002
    yearInput.addEventListener('input', function() {
        const yearValue = parseInt(yearInput.value, 10);
        if (yearValue >= 2002) {
            yearInput.setCustomValidity("Year must be before 2002.");
        } else {
            yearInput.setCustomValidity("");
        }
    });
</script>

	 	<tr style="vertical-align: top"> 
      <td style="text-align: right" class="LabelColor" nowrap="nowrap"> 
        <label for="nationality"> Nationality</label>      </td> 
      <td colspan="2" class="TitleColor"> 
        <input style="width:70%;height:30;font-size:25px;" type="text" id="nationality" name="nationality" />          </td> 
    </tr> 
    <tr style="vertical-align: top"> 
  <td style="text-align: right;font-size:25px;" class="LabelColor" nowrap="nowrap"> 
    <label for="mstatus"> Marital Status</label>      
  </td> 
  <td colspan="2" class="TitleColor"> 
    <select name="mstatus" style="width:70%;height:30;font-size:25px;">
      <option value="Single">Single</option>
      <option value="Married">Married</option>
      <option value="Widowed">Widowed</option>
    </select> 
  </td> 
</tr>

	 	<tr style="vertical-align: top"> 
      <td style="text-align: right;font-size:25px;" class="LabelColor" nowrap="nowrap"> 
        <label for="title"> Title</label>      </td> 
      <td colspan="2" class="TitleColor"> 
        <label> 
        <select name="title" style="width:70%;height:30;font-size:25px;"> 
          <option>Dr</option> 
          <option>Teacher</option> 
          <option>Enginer</option> 
              </select> 
        </label>        </td> 
    </tr> 
	 	<tr style="vertical-align: top"> 
      <td style="text-align: right;font-size:25px;" class="LabelColor" nowrap="nowrap" > 
        <label for="status"> Status</label>      </td> 
      <td colspan="2" class="TitleColor"> 
        <label> 
        <select name="status" style="width:70%;height:30;font-size:25px;"> 
          <option>Working</option> 
          <option>Retired</option> 
              </select> 
        </label>        </td> 
    </tr> 
	 	
   
     
     
    </tr> 
    <tr style="vertical-align: top" class="FooterColor"> 
      <td> </td> 
	 	  <td colspan="3"> 
        <input style="width:30%;height:40;font-family:verdana;font-size:25px;" type="submit" name="register" value="Register" /> 
        <label> 
        <input style="width:30%;height:40;font-family:verdana;font-size:25px;" type="reset" name="Submit" value="Reset" /> 
        </label></td> 
    </tr> 
  </table> 
</form> 
</div>
</body>
</html>