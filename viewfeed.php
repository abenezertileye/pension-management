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
 <title>View Feedbacks</title>
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
       $sql="select role from users where username='$user' ";
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
<div style="float:right;width:70%;margin:auto;margin-top:100px;">
 <?php 
	   $sqlf="select distinct id from feeds where checked=0";
       $sqlfr=$mysqli->query($sqlf) or die($mysqli->error);
       if($sqlfr->num_rows>0){
		   echo "<form action='viewfeed.php' method='POST' style='width:80%;font-family:verdana;font-size:22px;border:solid blue 1px;background-color:white;padding:30px;margin:auto;text-align:center;'>";
		   echo "Choose ticket"."<br>"."<select name='ticket' style='width:20%;font-size:22px;'>";
		   while($sqlfeed=$sqlfr->fetch_assoc()){
			 echo "<option style='width:100%;font-size:22px;text-align:center;'>".$sqlfeed['id']."</option>";
		   }
		   echo "</select>"."<br>"."<br>";
		   
		   echo "<input type='submit' name='find' value='Search' style='width:30%;font-size:22px;'>"."<br>";
		       if(isset($_POST['find'])){
				   echo "<br>";
				   echo "<textarea name='response' rows='4' cols='50'>"."</textarea>"."<br>";
				   echo "<br>";
				   echo "<input type='submit' name='review' value='Checked' style='width:30%;font-size:22px;'>"."<br>"; 
			   }
		   echo "</form>";
	   }else{
		   echo "<p style='width:50%;font-family:verdana;font-size:22px;'>"."There is no unchecked feedback"."</p>";
	   }	
	   
       if(isset($_POST['find'])){
		   $id=$_POST['ticket'];
		   $_SESSION['tick']=$id;
		   $sqlfeed="select * from feeds where id=$id and checked=0";
		   $sqlfeedr=$mysqli->query($sqlfeed) or die($mysqli->error);
	         if($sqlfeedr->num_rows>0){
				 $feedfetch=$sqlfeedr->fetch_assoc();
				 echo "<div style='border:solid blue 1px;background-color:white;width:87%;margin:auto;font-family:verdana;font-size:22px;text-align:center;'>";
				 echo "<div style='border:solid blue 1px;background-color:white;'>";
				 echo "<p>"."Tiket:".$feedfetch['id']."</p>";
				 echo "<p>"."Name:".$feedfetch['fullname']."</p>";
				 echo "<p>"."Comment:".$feedfetch['comment']."</p>";
				 ?>
				 <img src="<?php echo $feedfetch['attach']; ?>" width=500 height=500 style="padding:50px;" > 
                 </div>	
	<?php		 }
	   }
            if(isset($_POST['review'])){
			      if(empty($_POST['response'])){
					 $errmsg="Fill in your response"; 
				  }else{
					  $response=$_POST['response'];
					  $id=$_SESSION['tick'];
					  $s="update feeds set checked=1, response='$response'";
					  $mysqli->query($s) or die($mysqli->error);
				  }	
			}	   
	?>
 
</div>
</body>
</html>