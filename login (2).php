<?php 
include 'dbcon.php'; 
session_start();          
if(isset($_SESSION["fullname"])){
   header("location: teacher.php");
    exit;
}else{ 
$conf=1;
require_once "dbcon.php"; 
$fullname = $password = $newpass = $newpassc = "";
$fullname_err = $password_err = $login_err = $newpass_err = $newpassc_err= "";
//if($_SERVER["REQUEST_METHOD"] == "POST"){
if(isset($_POST['check'])){
    // Check if fullname is empty
    if(empty(trim($_POST["fullname"]))){
        $fullname_err = "Please enter Full name.";
    } else{
        $fullname = trim($_POST["fullname"]);
    }
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
	} else{
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if(empty($fullname_err) && empty($password_err)){
        $sql = "SELECT id,name,sex,subject,education,role,password,confirm,section FROM teacher WHERE name = '$fullname'";
		$result=$mysqli->query($sql) or die($mysqli->error);
        $all=$result->num_rows;
        if($all>0){
        $validation=$result->fetch_assoc();
		$vid=$validation['id'];
		$vfullname =  $validation['name'];
		$subject=$validation['subject'];
		$ved=$validation['education'];
		$role=$validation['role'];
		$confirm=$validation['confirm'];
		$vpass=$validation['password'];
		$vsec=$validation['section'];
          }
		if($result->num_rows==1){
			   //echo "<h1 style='color:white;'>".$vpass."</h1>";
               //echo "<h1 style='color:white;'>".$password."</h1>";			   
		        if($confirm==1) {
					if(password_verify($password,$vpass)){
              // Store data in session variables
				  $_SESSION["id"] = $vid;
				  $_SESSION["name"] = $vfullname;
				  $_SESSION["subject"] = $subject;
				  $_SESSION["education"] = $ved;
				  $_SESSION["role"] = $role;
				  $_SESSION["confirm"] = $confirm;
				  $_SESSION["grade"]=$vsec;
				  $_SESSION["fullname"]=true;
                  $_SESSION['start'] = time();
                  $_SESSION['expire'] = $_SESSION['start'] + 3600;
				         //if($role=='Teacher'){
						//	header("location: teacher.php"); 
						// }
                            header("location: teacher.php");      
					    }else{
							$password_err="Your password is not correct.";
							$conf=1;
						}
						}else{
						  	$conf=0;
						}
						} else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        } 
    }
}else if(isset($_POST['update'])){
	 // Check if fullname is empty
    if(empty(trim($_POST["fullname"]))){
        $fullname_err = "Please enter Full name.";
    } else{
        $fullname = trim($_POST["fullname"]);
    }
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
	 // Check if New Password is empty
    if(empty(trim($_POST["newpass"]))){
        $newpass_err = "Please enter new password.";
	}else if(strlen(trim($_POST["newpass"])) < 8){
		$password_err = "Minimum password length should be 8.";
    } else{
        $newpass = trim($_POST["newpass"]);
    }
	 // Check if password is empty
    if(empty(trim($_POST["newpassc"]))){
        $newpassc_err = "Please confirm your new password.";
    } else{
        $newpassc = trim($_POST["newpassc"]);
    }
	    if(empty($fullname_err) && empty($password_err) && empty($newpass_err) && empty($newpassc_err)){
			if($newpass==$newpassc){
			$newpass = password_hash($newpass, PASSWORD_DEFAULT);		
			$sql = "update teacher set password='$newpass',confirm=1  WHERE name = '$fullname'";
			$result=$mysqli->query($sql) or die($mysqli->error);
			}else{
			$newpassc_err="Password didnot match";	
			$newpass_err="Password didnot match";
			}	    
          }
		}	
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=0.5">
  <title>Login Page</title>
  <link rel="stylesheet" type="text/css" href="css2.css">
<link rel="icon" type="image/png" href="ready.png" sizes="32x32">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
</head>
<body style="background-image:url('bg17.jpg');">
<?php 
         include 'dbcon.php';  
           $q = "SELECT * FROM homeslide "; 
          $ads=$mysqli->query($q) or die($mysqli->error);  
          $row=$ads->fetch_assoc();  
          ?>
<div style="margin-bottom:-30px;margin-top:27px; margin-left:-7px; width:101%; box-shadow: 2px 2px 4px 2px rgba(0, 0, 0, 0.5); ">
<div class="mySlides fade" style="width:100%;  ">
  <img style="width:100%;" src="<?php  echo $row['img1']; ?>  "  width:100% height=150> 
</div>
<div class="mySlides fade" style="width:100%; ">
  <img style="width:100%; "  src="<?php  echo $row['img2']; ?>  "  width:100% height=150> 
</div>
<div class="mySlides fade" style="width:100%; ">
  <img style="width:100%; "  src="<?php  echo $row['img3']; ?>"  width:100% height=150>
</div>
<div class="mySlides fade" style="width:100%; ">
  <img style="width:100%; " src="<?php  echo $row['img4']; ?>"  width:100% height=150>
</div>
<div class="mySlides fade" style="width:100%; ">
  <img style="width:100%; " src="<?php  echo $row['img5']; ?>"  width:100% height=150>
</div>
<div class="mySlides fade" style="width:100%; ">
  <img style="width:100%; " src="<?php  echo $row['img6']; ?>"  width:100% height=150>
</div>
<div class="mySlides fade" style="width:100%; ">
  <img style="width:100%; " src="<?php  echo $row['img7']; ?>"  width:100% height=150>
</div>
</div> 
<br>
<script>
var slideIndex = 0;
showSlides();
function showSlides() {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  for (i = 0; i < slides.length; i++) {
    slides[i].className = slides[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  slides[slideIndex-1].className += " active";
  if(slideIndex==4 || slideIndex==7)
  setTimeout(showSlides, 3000); // Change image every 5 seconds
  else
  setTimeout(showSlides, 1000);
}
</script>
 <div style="margin-top:16px;margin:auto;width:100%;border-left-color:red;border-radius:5px; box-shadow: 2px 2px 4px 2px rgba(0, 0, 0, 0.5);">
   <hr color=#bf0000 style="margin-bottom:-6px; width:101%; margin-left:-7px;">
   <hr color=black  style="margin-bottom:-6px; width:101%; margin-left:-7px;">
   <hr color=#bf0000 style="margin-bottom:-6px; width:101%; margin-left:-7px;">
   <hr color=black  style="margin-bottom:-6px; width:101%; margin-left:-7px;">
   <hr color=#bf0000 style="margin-bottom:-6px; width:101%; margin-left:-7px;">
   <hr color=black  style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=#bf0000 style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=black  style="margin-bottom:-8px;  width:101%; margin-left:-7px;">
   <hr color=#bf0000 style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=black  style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=#bf0000 style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=black  style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=#bf0000 style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=black  style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=#bf0000 style="margin-bottom:-8px; width:101%; margin-left:-7px;">
   <hr color=black  style="margin-bottom:20px;  width:101%; margin-left:-7px;">
</div>
      <hr>
      <div style="width:80%; margin:auto;  padding:5px; border:1px solid blue; background-color:white;border-radius:10px;
	              box-shadow: 2px 2px 4px 2px rgba(0, 0, 0, 0.7);">  
        <p style="font-size:23px;text-align:center;background-color:#AECDCF;margin:auto;"><b>Please fill in your login information</b></p>
		<hr>
		<p style="font-size:23px;color:tomato;text-align:center;margin:auto;">
		    <?php echo $login_err; ?>
		</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <p style="width:80%; margin:auto;">
                <label style="width:90%; margin:auto;text-align:center;">Fullname</label> <br>
                <input style="width:90%; margin:auto;text-align:center;" type="text" name="fullname"  required value="<?php echo $fullname_err; ?>">
                <br><span><?php echo $fullname_err; ?></span>    
            </p>
			<p style="width:80%; margin:auto;">
                <label style="width:90%; margin:auto;text-align:center;">Password</label> <br>
                <input style="width:90%; margin:auto;text-align:center;" type="password" name="password" required value="<?php echo $password_err; ?>">
                <br><span style="color:red;" ><?php echo $password_err; ?></span>
            </p>
			<?php if(isset($_POST['check']) && $conf==0){ 
			echo "<p style='width:80%; margin:auto;'>
                <label style='width:90%; margin:auto;text-align:center;'>New Password</label> <br>
                <input style='width:90%; margin:auto;text-align:center;' type='password' name='newpass' required value='<?php echo $newpass_err; ?>'>
                <span ><?php echo $newpass_err; ?></span>
            </p>
			<p style='width:80%; margin:auto;'>
                <label style='width:90%; margin:auto;text-align:center;'>Confirm Password</label> <br>
                <input style='width:90%; margin:auto;text-align:center;' type='password' name='newpassc' required value='<?php echo $newpassc_err; ?>'>
                <span ><?php echo $newpassc_err; ?></span>
            </p> ";			     
			}
			?>
			<br>
			<p style="width:80%; margin:auto;">
	              <?php if(isset($_POST['check'])){
					        if($conf==0){
					echo "<input style='width:50%; margin:auto;text-align:center;' name='update' type='submit' value='Update Password'>";		
							}
				 }else if(isset($_POST['check']) || $conf==1){
						//	  if($_SERVER["REQUEST_METHOD"] == "POST" && $conf==1)  
				//	echo "<input style='width:50%; margin:auto;text-align:center;' name='check' type='submit' value='Login'>"; 
				 }
                     					   
				  ?>
			</p>
			<br> 
            <p style="width:80%; margin:auto;text-align:center;">
			<?php if($conf==1){
				  echo "<input style='width:50%; margin:auto;text-align:center;' name='check' type='submit' value='Login'>"; 
			  } ?>
			</p>
        </form>
		
	</div>
</body>
</html>