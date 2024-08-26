<?php
$servername = "localhost";
$username = "oceaneba_db";
$password = "Ocean@2021";
$db='oceaneba_db';


$mysqli = new mysqli($servername,$username,$password,$db);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

date_default_timezone_set('Asia/Kolkata');
$date=date('H:i');
$vendor="SELECT * FROM `vendors` WHERE `is_active`='1' AND `close_time` < '$date'";
$sqlq=mysqli_query($mysqli,$vendor);
$fetch=mysqli_fetch_all($sqlq, MYSQLI_ASSOC);
for($i=0;$i <= count($fetch); $i++)
   {  
       $sqlu="UPDATE `vendors` SET `is_active`='0' WHERE `id`='".$fetch[$i]['id']."'";
       $update=mysqli_query($mysqli,$sqlu);
   }
   

$vendor1="SELECT * FROM `vendors` WHERE `is_active`='0' AND `open_time` < '$date'";
$sqlq1=mysqli_query($mysqli,$vendor1);
$fetch1=mysqli_fetch_all($sqlq1, MYSQLI_ASSOC); 
// echo count($fetch1);
for($j=0;$j <= count($fetch1); $j++)
  {
      $sqlu1="UPDATE `vendors` SET `is_active`='1' WHERE `id`='".$fetch1[$j]['id']."'";
      $update1=mysqli_query($mysqli,$sqlu1);
  }  
?>
