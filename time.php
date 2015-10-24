<?php
session_start();
if(isset($_SESSION["name"])==false) {
 echo "<script>window.location = 'Bunkometer1.php'</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
body {
    background-color: rgb(50,150,0);
}
#header {
    background-color:black;
    color:white;
    text-align:center;
    padding:5px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
#bunked {
display: inline-block;
padding: 5px;
}
</style>
</head>
<body>

<div id="header">
<h1>BUNK-O-METER</h1>
</div>

<p style="position:fixed;top:125px;left:50px;">You are logged in as <?php echo $_SESSION["name"];?></p>
<form method="post" action="logout.php" style="position:fixed;top:40px;right:50px;"> 
<input type="submit" value="Log Out" >
</form>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunkometer";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$name = $_SESSION["name"];
$subject = $_POST["subject"];
$sql ="SELECT * FROM time where Name='$name' AND Subject='$subject'";
$result = $conn->query($sql);
$conn->close();
?>
<table style="width:100%;position:fixed;top:200px;">
  <tr>
    <th>Bunked Class No</th>
    <th>Bunked on or before</th>		
  </tr>
<?php
while($row = $result->fetch_assoc()){
$f1 = $row["Classno"];
$f2 = $row["Bunkedon"];
$temp = date_create($row["Bunkedon"]);
$date = date_format($temp,"Y-m-d");
?>
<tr>
    <td><?php echo $f1;?></td>
    <td><?php echo $date;?></td>
</tr>
<?php }
?>

<input type="button" value="Back" onclick="location.href='Bunkometer2.php'" style="position:fixed;top:110px;left:30px;">
</body>
</html>