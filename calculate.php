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


$success=0;
$fname = $_POST["fname"]; 
$sex = $_POST["sex"]; 
$dd = $_POST["dd"]; 
$mm = $_POST["mm"]; 
$yyyy = $_POST["yyyy"]; 
$type = $_POST["org"];
$nationality = $_POST["nationality"]; 
$mstatus = $_POST["mstatus"]; 
$title = $_POST["title"]; 
$status = $_POST["status"]; 
$rdate =  date("y-m-d"); 
$bod = "$yyyy-$mm-$dd"; 
$id=$_SESSION['sid']; 
 
if(@$_REQUEST['register']) 
{ 
  if(empty($fname) or empty($sex) or empty($dd) or empty($mm) or empty($yyyy) or empty($type) or empty($nationality) or empty($mstatus) or empty($title) or empty($status)  ) 
	 	 	 	{ 
	 	 	 	$msg="Fill the fields"; 
	 	 	 	} 
	 	 	else 
	 	 	 	{ 
			 //   echo "here 1.....";
	 	 	 	$query ="select * from pensioner ORDER BY id DESC LIMIT 1"; 
				$query=$mysqli->query($query);
				//$t=$query->num_rows;	 	 	
			 if($query->num_rows >=1) 
	 	 	 	{ 
			  $t=$query->fetch_assoc();
			   
			    if($type=='Pub'){
					$orgid="Pub".$t['id']+1;
					$ssno=$orgid.$t['id']+3;
				}else{
					echo $orgid; 
					$orgid="Pri".$t['id']+1;
					$ssno=$orgid.$t['id']+3;
				}
	 	 	  } 
	 	 	 	else  
	 	 	 	{
					
				}
              // echo "here 3.....";					
	 	 	 	 	
	 	 	 	 	//$fp=addslashes(file_get_contents($_FILES['photo']['tmp_name'])); 
	 	 	 	 	$query = "INSERT INTO Pensioner(ssn,fname,sex,bod,orgtype,nationality,mstatus,title,status,rdate,orgid,fp)  VALUES('$ssno', '$fname','$sex', '$bod','$type', '$nationality', '$mstatus',
					'$title','$status', '$rdate','$orgid', '$fp')"; 
	 	 	 	 	$result = $mysqli->query($query) or die($mysqli->error); 
	 	 	 	 	 	if($result) 
	 	 	 	 	 	{ 
	 	 	 	 	 	 $msg="You Have Successfully Registered a new User" ; 
						 $success=1;
	 	 	 	 	 	} 
	 	 	 	 	 	else 
	 	 	 	 	 	{ 
	 	 	 	 	 	echo $ssno.$fname.$sex.$bod.$type.$nationality.$mstatus.
					      $title.$status.$rdate.$orgid.$fp;
						die('Error : ('. $con->errno .') '. $con->error); 
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
   <a href="pensioner.php" style="float:left;">Register new pensioner</a> <br>
     <a href="beneficiery.php" style="float:left;">Register Beneficiery</a> <br>
   <a href="create.php" style="float:left;">Add new user</a><br>
   <a href="report.php" style="float:left;">Generate report</a><br>
   <a href="viewfeed.php" style="float:left;">View feedbacks</a><br>
   <a href="calculate.php" style="float:left;">Calculate Pension</a><br>
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
     
  <div width="99%" border="0" cellpadding="8" cellspacing="10" style="font-family:verdana;font-size:23px;"> 
    <tr> 
      <td colspan="3" class="HeaderColor">        
		<h2 style="text-align:center;background-color:#D9D9D9;width:100%;">Calculate Pension </h2> 
        <hr color=blue>	
         <form action="calculate.php" method="POST">
		   <label>Enter Pensioner SSN</label>
		   <input name="ssn" type="text" style="height:40;font-size:22px;">
		   <input type="submit" name="submit" value="Search" style="font-size:23px;height:40;">
		 </form>
  </div> 
   <?php  if(isset($_POST['submit'])){     
     
            $ssn=$_POST['ssn'];   
			$sq="select * from pensioner where ssn='$ssn' ";
			$sqr=$mysqli->query($sq);
			if($sqr->num_rows > 0){
				$sqrf=$sqr->fetch_assoc();
				$dob=$sqrf['bod'];
				$date=new DateTime("$dob");
                $now = new DateTime();
				$interval = $now->diff($date);
				$age=$interval->y;
				$sal=$sqrf['salary'];
				$service=$sqrf['service'];
				    if($age>=60 && $service >=10){
						$percent=(($service-10)*1.25+30);
						   if($percent>70){
							   $percent=70;
						   }else{
						   $pension=($percent/100)*$sal;
						   }
						echo "<div style='border:dotted green 5px;width:90%;margin:auto;padding:20px;font-family:Helvetica;font-size:20px;line-height:30px;background-color:white;'>";
						echo "Name:".$sqrf['fname']."<br>";
						echo "Age:".$age."<br>";
						echo "Service years:".$service."<br>";
						echo "Preceeding average salary:".$sal."<br>";
						echo "Payment precent:".$percent."<br>";
						echo "<hr>";
						echo "<b>"."Pension amount is:".$pension."</b>"."<br>";
					    echo "</div>";
						$squp="update pensioner set fp=$pension";
						$mysqli->query($squp) or die($mysqli->error);
					}else{
						echo "<p style='text-align:center;color:red;font-size:22px;'>"."You are not entitled to start your payment yet."."</p>";
					}

			}else{
			echo "<p style='text-align:center;font-size:30px;font-family:verdana;color:red;'>"."<b style='font-size:50px;color:black;'>!</b>"."Data not found"."</p>";}
   
   ?>
       
		 
   <?php }else{?>
      <p style="font-family:verdana;font-size:22px;text-align:center;background-color:white; width:100%;">
	     <ul style="font-family:verdana;font-size:22px;text-align:left;background-color:white; width:95%;line-height:35px;">
		 <li>The period of service of a public servant shall begin with the date of his employment or appointment.</li>
		 <li>The retirement age of a public servant shall, based on the date of birth registered when he was employed for the first time</li>
		 <li>For public servant, it is 60 years.</li>
		 <li>A public servant who has completed at least 10 years of service and retires upon attaining retirement age shall receive retirement pension for life;</li>
		 <li>The retirement pension of any public servant who has completed 10 years of service shall be 30% of his average salary for the last three years preceding retirement</li>
		 <li>This shall be increased, for each year of service beyond 10 years:
			a) by 1.25% for a public servant other than member of the defense force or the police;
			b) by 1.65% for a member of the defense force or the police.</li>
		 <li>The retirement pension to be paid may not exceed 70% of the average salary of the public servant</li> 

		 </ul>
		  
	   </p>
   <?php } ?>

  
</div>
</body>
</html>