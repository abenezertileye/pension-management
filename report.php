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



<div style="background-color:;text-indent:0px;margin-top:50px;width:70%;font-family:verdana;font-size:25px;float:right;line-height:40px;background-color:white;border-radius:10px;padding:25px;height:90%;padding-bottom:500px;" >
 <form method="POST" style="font-family:verdana;font-size:229x;">
    <label for="ssnumber">Enter SSN</label>
   <input type="text" name="ssnumber" >
   <input type="submit" name="search" value="Search">
 </form>
 <hr>
 <?php 
       if(isset($_POST['search'])){
			$id=$_POST['ssnumber'];
			$searchsql="select * from pensioner where ssn='$id'";
			$runsql=$mysqli->query($searchsql) or die($mysqli->error);
			if($runsql->num_rows>0){
			   $fetchsql=$runsql->fetch_assoc();
			       echo "<table style='width:100%;margin-bottom:50px;font-size:18px;'>";
				   echo "<caption style='font-family:verdana;font-size:22px;'>"."Pensioner information"."</caption>"; 
				   echo "<tr>";
				   echo "<td>"."SSN:".$fetchsql['ssn']."</td>"."<th>"."Name"."</th>"."<th>"."Birth date"."</th>";
				   echo "<th>"."Organization"."</th>"."<th>"."Nationality"."</th>"."<th>"."Marital status"."</th>";
				   echo "</tr>";
				   
				   echo "<tr>";?>
				   <td><img width=100 height=100 src='<?php echo $fetchsql['photo'];  ?>'></td>
				   <?php
				   echo "<td>".$fetchsql['fname']."</td>";
				   echo "<td>".$fetchsql['bod']."</td>";
				   echo "<td>".$fetchsql['orgtype']."</td>";
				   echo "<td>".$fetchsql['nationality']."</td>";
				   echo "<td>".$fetchsql['mstatus']."</td>";
				   echo "</tr>";
			       echo "</table>";
			   }	
			}
			
		
 ?>
 <hr>
 <table style="width:90%;margin:auto;">
   <caption style="font-family:verdana;font-size:22px;">Statistics of registered pensioners</caption>
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
 <br><br>
 <?php  
       $sqyear="select distinct rdate from pensioner";
       $sqyearun=$mysqli->query($sqyear) or die($mysqli->error);         
         if($sqyearun->num_rows>0){
			 while($sqyearf=$sqyearun->fetch_assoc()){
				  $yr=$sqyearf['rdate'];
				  $sqq="select * from pensioner where rdate='$yr' and sex='M'";
				  $sqqr=$mysqli->query($sqq) or die($mysqli->error);
				  $m=$sqqr->num_rows;
				  $sqq="select * from pensioner where rdate='$yr' and sex='F'";
				  $sqqr=$mysqli->query($sqq) or die($mysqli->error);
				  $f=$sqqr->num_rows;
				 echo "<table style='width:90%;margin:auto;'>";
				 echo "<caption style='text-align:left;font-family:verdana;font-size:22px;'>"."Date:".$sqyearf['rdate']."</caption>";
				 echo "<tr>";
				 echo "<th>"."Male"."</th>"."<th>"."Female"."</th>"."<td>"."Total"."</td>";
				 echo "</tr>";
				 echo "<tr>";
				 echo "<td>".$m."</td>"."<td>".$f."</td>"."<td>".$m+$f."</td>";
				 echo "</tr>";
				 echo "</table>";
				 echo "<br>";
			 }
			 
		 }
  
  ?>
 
   
    
	
</div>
</body> 
</html> 

