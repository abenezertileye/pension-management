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
  $full_err=$comment_err=$errmsg="";
    if(isset($_POST['submit'])){
		if(empty(trim($_POST["comment"]))){
			$comment_err="Please enter your comment";
		}else{
			$comment=$_POST["comment"];
		}
		
		  $qfile=$_FILES['uploadfile']['name'];
		  $tname=$_FILES['uploadfile']['tmp_name'];
		  $folder=$qfile;
		
		
	 if(empty($comment_err)){
		  
		 $user=$_SESSION['user'];
		 $sqlcreate="insert into feeds(fullname,comment,attach) values('$user','$comment','$qfile')"; 
		 $resultcreate=$mysqli->query($sqlcreate) or die($mysqli->error);
	     $errmsg="Thank you for your feedback";
		   if($resultcreate){
			   move_uploaded_file($tname,$folder);
		   }
	  }
	}
 
 ?>

<html>
<head>
 <title>Feedback submission </title>
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
	<a href="register_company.php" style="float:left;">Register new company</a> <br>

   <a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
     <a href="choose_company_2.php" style="float:left;">Register Beneficiery</a> <br>
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
   <a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
   <a href="report.php" style="float:left;">Generate report</a><br>
   <a href="logout.php" style="float:left;">Logout</a><br>
     <?php } 
	  else if($roles['role']=="Organization"){	 ?>  
   <a href="choose_company.php" style="float:left;">Register new pensioner</a> <br>
   <a href="report.php" style="float:left;">Generate report</a><br>
   <a href="logout.php" style="float:left;">Logout</a><br>  
	  <?php } ?>

</div>
<div style="float:right;width:70%;margin:auto;">
 
 <div >
         <?php //echo $errmsg."<br>"; ?>
        <form method="POST" enctype="multipart/form-data" style="background-color:white;font-family:verdana;font-size:20px;border-radius:15px;padding:20px;line-height:45px;margin-top:50px;">
				
				<p style="text-align:center;background-color:#D9D9D9;width:100%;">
					Write your feedback here
				</p>
				<p>
				<textarea style="resize:none;font-size:21px;width:100%;margin:auto;" name="comment" rows="4" cols="30"></textarea>
				</p>
				<p>
				  Attach feedback files <input  type="file" name="uploadfile" >
				</p>
				<p>
					<input style="font-size:21px;width:100%;margin:auto;" type="submit" value="Send" name="submit" />
				</p>
				
		</form>
			
  </div>
        
 <?php 
	   $sqlf="select * from feeds where fullname='$user'";
       $sqlfr=$mysqli->query($sqlf) or die($mysqli->error);
       if($sqlfr->num_rows>0){
		   while($sqlfeed=$sqlfr->fetch_assoc()){
			 echo $errmsg."<br>";  
			   if($sqlfeed['checked']==0){
			 echo "<br>";
			 echo "<p style='border-top:solid blue 1px;border-bottom:solid blue 1px;width:100%;margin:auto;background-color:white;font-family:verdana;font-size:20px;'>";
			     echo "Your Ticket is:"."<b>"."<u>".$sqlfeed['id']."</u>"."</b>"."<br>"; 
                 echo " We will address and respond to your issues sooner.";			 	
		     echo "</p>";  
			   }else{
				  echo "<br>";
			 echo "<p style='border-top:solid blue 1px;border-bottom:solid blue 1px;width:100%;margin:auto;background-color:white;font-family:verdana;font-size:20px;'>";		 
				echo "Dear "."<b>"."<u>".$sqlfeed['fullname']."</u>"."</b>"."<br>";  
           		echo $sqlfeed['response']."<br>";		
		     echo "</p>";   
			   }			 
		   }
	   }	   
	
	?>
 

</div>

</body>
</html>