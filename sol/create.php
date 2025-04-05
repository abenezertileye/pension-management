<?php 
session_start(); include_once('connection.php'); error_reporting(1); 
error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");
if(!isset($_SESSION['user'])){
						header("Location:login.php");
					}else{
						$now = time();
					if ($now > $_SESSION['expire']) {
						session_destroy(); 
					}
					}

  $full_err=$role_err=$errmsg="";
    if(isset($_POST['submit'])){
		if(empty(trim($_POST["fullname"]))){	
			$full_err="Please enter full name";
		}else{
			$full=$_POST["fullname"];
		}
		if(empty(trim($_POST["role"]))){
			$role_err="Please choose the role";
		}else{
			$role=$_POST["role"];
		}
	  	
	  $sqlfull="select * from users where username='$full' ";
	  $resultfull=$mysqli->query($sqlfull) or die($mysqli->error);
	  if($resultfull->num_rows==1){
		  $errmsg="This user is already created";
	  }else if(empty($full_err) && empty($role_err)){
		 $pass=rand(12345678,87654321); 
		 echo $pass;
		 //$pass=password_hash($pass, PASSWORD_DEFAULT);
		 $sqlcreate="insert into users(username,password,role,status) values('$full','$pass','$role',0)"; 
		 $resultcreate=$mysqli->query($sqlcreate) or die($mysqli->error);
	     $errmsg="Account created successfully";
		// mail("selamubachore@gmail.com","Your default password",$pass);
	  }
	}
 
 ?>

<html>
<head>
 <title>Account creation </title>
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
<div style="float:right;width:70%;margin:auto;">
 
 <form  method="post" enctype="multipart/form-data" action="create.php"
  style="background-color:white;font-family:verdana;font-size:23px;border-radius:15px;padding:20px;line-height:45px;margin-top:50px;"> 
     <p style="color:red;border:solid blue 1px;"> 
	     <h3 style="font-family:verdana;font-size:23px;">Create account</h3>
	     <?php 
		       echo "<font color=red>".$errmsg." ".$full_err." ".$role_err."</font>";
		 ?> 
	 </p>
	 <hr>
	 <br>
     <label for="fullname">Fullname</label><br>
	 <input name="fullname" type="text" style="width:70%;height:30;"><br>
	 <label for="role">Role</label><br>
	 <select name="role" style="width:70%;height:30;font-size:23px;">
	     <option>Admin</option>
		 <option>Pensioner</option>
		 <option>Clerk</option>
		 <option>Organization</option>
	 </select><br><br>
     <input name="submit" type="submit" value="Create" style="width:50%;height:30;font-size:23px;">
</form> 
</div>
</body>
</html>