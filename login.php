
<?php 
session_start();
include 'connection.php';
if(isset($_SESSION["user"])){
   header("location: main.php");
    exit;
}else{ 
   $msg=$username_err=$pass_err=$newuser="";
  if(isset($_POST['submit'])){
	  if(empty(trim(($_POST['user'])))){
	    $username_err="Please enter your username";
	  }else{
		$user=$_POST['user'];  
	  }  
      if(empty(trim(($_POST['pass'])))){
	    $pass_err="Please enter your Password";
	  }else{
		$pass=$_POST['pass']; 			
	  }
    if(empty($username_err) && empty($pass_err)){
	$sql="select * from users where username='$user' and password='$pass' ";
	$result=$mysqli->query($sql) or die($mysqli->error);
	if($result->num_rows==1){
		//echo "<h1>user there</h1>";
		$users=$result->fetch_assoc();
	    if($users['status']==1){
			
			     // echo "<h1>active</h1>";
				  $_SESSION['user']=$users['username'];
				  $_SESSION['start'] = time();
                  $_SESSION['expire'] = $_SESSION['start'] + 3600;
			header("location: main.php"); 
			
				
		  }else{
			$newuser="yes"; 
		  }
	}else{
		$msg="Incorrect username or password";
	}
	
	}
  }
  if(isset($_POST['update'])){
	  if(empty(trim(($_POST['user'])))){
	    $username_err="Please enter your username";
	  }else{
		$user=$_POST['user'];  
	  }  
      if(empty(trim(($_POST['newpass1'])))){
	    $pass_err="Please enter your Password";
	  }else{
		$pass1=$_POST['newpass1']; 			
	  }
	  if(empty(trim(($_POST['newpass2'])))){
	    $pass_err="Please enter your Password";
	  }else{
		$pass2=$_POST['newpass2']; 			
	  }
    if(empty($username_err) && empty($pass_err)){
		 if($pass1==$pass2){
	//$pass=password_hash($pass1, PASSWORD_DEFAULT);		 
	$sql="update users set password='$pass1', status=1 where username='$user'";
	$result=$mysqli->query($sql) or die($mysqli->error);
				  $_SESSION['user']=$users['username'];
				  $_SESSION['start'] = time();
                  $_SESSION['expire'] = $_SESSION['start'] + 3600;
				  header("location: main.php"); 
			
	}else{
		$msg="The new Password did not match. Try from the old again.";
	}
  }
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
	  color:white;
  } 
  a{
  
  text-decoration : none;
}
</style> 
</head> 
<body bgcolor="lightblue">
<div style="width:100%;background-color:gray;margin-bottom:-20px;">
   <img src="pssa.jpg" style="width:100%;">
</div> 
 
<div style="width:30%;margin:auto;background-color:white;text-align:center;font-family:verdana;font-size:20px;margin-top:50px;border-radius:10px;">
   <img src="log.jpeg" style="width:30%;">
   <?php  if(empty($newuser)) { ?>
   <form action="login.php" method="POST">
     <h2>Login Here</h2>
	 <?php echo $msg; 
	       echo $username_err;
		   echo $pass_err
	 ?><br>
	 <hr color=blue>
     <label>User name</label><br>
	 <input type="text" name="user" style="width:90%;height:40px;text-align:center;font-size:20px;"><br>
	  <label>Password</label><br>
	 <input type="password" name="pass" style="width:90%;height:40px;text-align:center;font-size:20px;"><br>
	 <input type="submit" name="submit" value="Login" style="width:40%;height:40px;margin-bottom:20px;margin-top:20px;font-size:25px;"> 
   </form>
   <?php }else{ ?>
   <form action="login.php" method="POST">
     <h2>Update Your password</h2>
	 <?php echo $msg; 
	       echo $username_err;
		   echo $pass_err;
	 ?><br>
	 <hr color=blue>
     <label>User name</label><br>
	 <input type="text" name="user" style="width:90%;height:40px;text-align:center;font-size:20px;"><br>
	  <label>New Password</label><br>
	 <input type="password" name="newpass1" style="width:90%;height:40px;text-align:center;font-size:20px;"><br>
	 <label>New Password again</label><br>
	 <input type="password" name="newpass2" style="width:90%;height:40px;text-align:center;font-size:20px;"><br>
	 <input type="submit" name="update" value="Update" style="width:40%;height:40px;margin-bottom:20px;margin-top:20px;font-size:25px;"> 
   </form>
   <?php } ?>
</div>
</body> 
</html> 


