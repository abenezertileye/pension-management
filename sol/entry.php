<?php
include 'dbcon.php';
session_start();
if(isset($_SESSION["studid"])=== true){
   header("location: student.php");
    exit;
}else{
require_once "dbcon.php";
// Define variables and initialize with empty values
$studid = $gffn =$getin_err= "";
$studid_err=$gffn_err=""; 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if studid is empty
    if(empty(trim($_POST["studid"])) || !(is_numeric(trim($_POST["studid"]))) ){
        $studid_err = "Please enter your id number.";
    } else{
        $studid = trim($_POST["studid"]);
    }
    // Check if Grandfather's fathername is empty
    if(empty(trim($_POST["grandff"]))){
        $gffn_err = "Please enter your Grandfather's father name.";
    } else{
        $gffn = trim($_POST["grandff"]);
    }
    // Validate credentials
    if(empty($studid_err) && empty($gffn_err)){
        // Prepare a select statement
        $sql = "SELECT * from student WHERE id = '$studid' and gffname='$gffn'" ;
		$result=$mysqli->query($sql) or die($mysqli->error);
        
        if($result->num_rows>0){
			$validation=$result->fetch_assoc();
			$fullname = $validation['name'];
			$grade=$validation['grade'];
			$_SESSION["sid"]=$studid;
              // Store data in session variables
                  $_SESSION["studid"] = true;
				  $_SESSION["fullname"] = $fullname;
				  $_SESSION['grade']=$grade;
				  $_SESSION['id']=$_SESSION["studid"];
                  $_SESSION['start'] = time();
                  $_SESSION['expire'] = $_SESSION['start'] + 3600;
				                          
                            // Redirect user to welcome page
                          header("location: student.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $getin_err = "Invalid ID or Grandfather's fathername.";
                        } 
    }
}
}
?>
 
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=0.5">
<style>
/* Fading animation */
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
} 

@-webkit-keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

/* On smaller screens, decrease text size 
 @media only screen and (max-width: 300px) {
//  .text {font-size: 11px}
//} */
</style>
  <title>Students' page</title>
  <link rel="stylesheet" type="text/css" href="css2.css">
  <style>
   
</style>
<link rel="icon" type="image/png" href="ready.png" sizes="32x32">
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
</head>
<body style="background-image:url('bg17.jpg'); width:80%; margin:auto;">

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

 <div style="margin-top:16px;background-color:white;margin:auto;width:100%;border-left-color:red;border-radius:5px; box-shadow: 2px 2px 4px 2px rgba(0, 0, 0, 0.5);">
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
<div style="margin:auto;border:solid blue 1px;text-align:center;background-color:dodgerBlue;padding-top:20px; box-shadow: 2px 2px 4px 2px rgba(0, 0, 0, 0.5); ">

</div>
<hr>
      <div style="width:50%; margin:auto;  padding:5px; border:1px solid blue; background-color:#FFFAFA;border-radius:10px;
	              box-shadow: 2px 2px 4px 2px rgba(0, 0, 0, 0.7);">  
        <p style="font-size:23px;text-align:center;background-color:#AECDCF;"><b>Please fill in your entry information to get in</b></p>
		<hr>
        <p style="width:90%; margin:auto; color:red; text-align:center;"><?php echo $getin_err; ?></p>  
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <p style="width:80%; margin:auto;">
                <label style="width:90%; margin:auto;text-align:center;">Student ID</label> <br>
                <input style="width:90%; margin:auto;text-align:center;" type="text" name="studid"  value="<?php //echo $studid_err; ?>">
                <p style="text-align:center; color:red;"><?php echo $studid_err; ?></p>    
            </p>
			<p style="width:80%; margin:auto;">
                <label style="width:90%; margin:auto;text-align:center;">Grandfather's father name</label> <br>
                <input style="width:90%; margin:auto;text-align:center;" type="text" name="grandff" value="<?php //echo $gffn_err; ?>">
                <p style="text-align:center; color:red;"><?php echo $gffn_err; ?></p>
            </p>
			<p></p>
            <p style="width:80%; margin:auto;text-align:center;">
                <input style="width:50%; margin:auto;text-align:center;" type="submit" value="Login">
            </p>
        </form>
	</div>
</body>
</html>