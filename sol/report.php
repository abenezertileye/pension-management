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
?>
<html> 
<head> 
<title>PSSSA Official Page</title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="css/layout.css" rel="stylesheet" type="text/css" /> 
<style> 
  a:hover{
	  color:black;
	  background-color:white;
  } 
  a{
  
  text-decoration : none;
}
th,td{
	padding:5px;
	background-color: #f2f2f2;
	border-bottom:1px solid blue;
	width: auto;
	text-align:center;
}
</style> 
</head> 
<body bgcolor="lightblue">

<div style="width:100%;background-color:gray;margin-bottom:-20px;">
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
   <a href="logout.php" style="float:left;">Logout</a><br>  
	  <?php } ?>

</div>



<div style="background-color:;text-indent:0px;margin-top:50px;width:70%;font-family:verdana;font-size:25px;float:right;line-height:40px;background-color:white;border-radius:10px;padding:25px;height:90%;" >
 <table style="width:50%;margin:auto;">
   <caption>Statistics of registered pensioners</caption>
   //filter 
   <tr>
       <th>Male</th><th>Female</th><th>Total</th>
   </tr>
   <tr>
       <th>
	        <?php 
			    $sq="select * from pensioner where sex='M'";
				$rsq=$mysqli->query($sq) or die($mysqli->error);
				echo $rsq->num_rows;
			?>
	   
	   </th>
	   <th>
	       <?php 
			    $sq="select * from pensioner where sex='F'";
				$rsq=$mysqli->query($sq) or die($mysqli->error);
				echo $rsq->num_rows;
			?>
	   </th>
	   <th>
	        <?php 
			    $sq="select * from pensioner";
				$rsq=$mysqli->query($sq) or die($mysqli->error);
				echo $rsq->num_rows;
			?>
	   </th>
   </tr>
 </table>
   
    
	
</div>
</body> 
</html> 

