<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunkometer";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$name = $_SESSION["name"];
$subject = $_POST["sub1"];

$sql="UPDATE bunkdetails SET Bunked = Bunked+1 WHERE Name='$name' AND Subjectname='$subject'";
if ($conn->query($sql) === TRUE){
echo "Successfully updated";
}
$sql="SELECT * FROM time WHERE Subject='$subject' AND Name='$name'";
if ($conn->query($sql) === FALSE){
   echo "Error selecting record: " . $conn->error;
}
$result = $conn->query($sql);
$classno = "";
if($result->num_rows == 1) {
  while($row = $result->fetch_assoc()) {
   if(strcmp($row["Classno"],"1")==0) {
     $classno = 2;
   } else {
   $ar = array();
   $ar = explode(' ',$row["Classno"],3);
   $classno = $ar[2] + 1;
   }
  }
} elseif ($result->num_rows > 1) {
   while($row = $result->fetch_assoc()) {
      $classno = $row["Classno"];
   }
   $classno = $classno +1;
} else {
   $classno = 1; 
} 
$sql="INSERT INTO time(Name, Subject, Classno) VALUES('$name','$subject',$classno)";
if ($conn->query($sql) === FALSE){
   echo "Error inserting record: " . $conn->error;
} 
$conn->close();
?>