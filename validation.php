<?php
session_start();
$psw = "";
$counter = 0;
$value = $_GET['query'];
$formfield = $_GET['field'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunkometer";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($formfield == "name") {
  $sql = "SELECT Name FROM userinfo";
  $result = $conn->query($sql);
  if($result -> num_rows > 0) {  
      while($row = $result->fetch_assoc()) {
         if(strcmp($row["Name"],$value)==0) {
             $counter = 1;
             echo "Name has already been entered";
         }
      }
   } 
   if($value == "") {
       echo "Name is required";
       $counter = 1;
   }
   if($counter == 0) {
        echo "<span>Valid</span>";
   }
}
if ($formfield == "pswsnup") {
$_SESSION["psw"] = $value; 
if($value == "" ) {
echo "Password is required";
} 
}
if($formfield == "repswsnup") {
if($value != "" ) {
if(strlen($value) == strlen($_SESSION["psw"])) {
if(strcmp($_SESSION["psw"],$value)!=0) {
   echo "Password is not the same. Please check what you have typed";
} else {
  echo "<span>Valid</span>";
}
} else{
   echo "No. of characters is not the same";
}
}
}
?>