</html>
<body style="background-image:url('bg19.jpg');">
 
</body>
</html>
<?php
//create connection credentials
$db_host ='localhost';
$db_name ='pension';
$db_user ='root';
$db_pass =''; 
//create mysqli object
$mysqli=new mysqli($db_host,$db_user,$db_pass,$db_name);

if($mysqli->connect_error){
   echo "<h1 style='color:red;'>"."Check your connection with the server."."</h1>";
   //printf("Hmmm not connected %s",$mysqli->connect_error);
exit();}
?>