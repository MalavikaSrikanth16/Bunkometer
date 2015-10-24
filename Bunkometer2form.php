<?php
session_start();
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
#option {
   height:30px;
   width:200px;
   position:fixed;
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
<input type="button" value="Enter Subject Details" id="option" onclick = "location.href='Subdetails.php'" style="top:200px;left:100px;" >

<?php
$f1 = $f2 = $f3 = $f4 = $f5 = "";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunkometer";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$name = $_SESSION["name"];

if (!empty($_POST['bunk'])) {
$subject = $_POST["sub"];

$sql="UPDATE bunkdetails SET Bunked = Bunked+1 WHERE Name='$name' AND Subjectname='$subject'";
if ($conn->query($sql) === FALSE){
   echo "Error updating record: " . $conn->error;
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
}

$sql ="SELECT * FROM bunkdetails where Name='$name'";
$result = $conn->query($sql);
$conn->close();
?>
<table style="width:100%;position:fixed;top:300px;">
  <tr>
    <th>Subjectname</th>
    <th>Min attendance</th>		
    <th>Total classes</th>
    <th>Bunked classes</th>
    <th>Bunks left</th>
  </tr>
<?php
while($row = $result->fetch_assoc()){
$f1 = $row["Subjectname"];
$f2 = $row["Minreqd"];
$f3 = $row["Total"];
$f4 = $row["Bunked"];
$maxbunk = ((100 - $row["Minreqd"])/100) * $row["Total"];
$bunksleftdec = $maxbunk - $row["Bunked"];
$bunksleft = floor($bunksleftdec);
if($bunksleft > 0) {
    if ($bunksleft == 1) {
        $f5 = 'You can still bunk '. $bunksleft . ' class!'; 
    } else {
        $f5 = 'You can still bunk '. $bunksleft . ' classes!'; 
    }
} elseif ($bunksleft == 0) {
    $minpercent = $row["Minreqd"];
    $f5 = 'Your attendance percentage is ' . $minpercent . ' % . Bunk one more class and you will have crossed the limit. Be careful!';
} else {
    $bunksextra = -$bunksleft;
    $percent = (($row["Total"] - $row["Bunked"])/$row["Total"]) * 100;
    $percentrounded = number_format($percent, 2);
    if($bunksextra == 1) {
        $f5 = 'Your attendance percentage has dropped below the minimum and is now ' . $percentrounded . ' % . Unfortunately you have bunked ' . $bunksextra .' extra class!';
    } else {
        $f5 = 'Your attendance percentage has dropped below the minimum and is now ' . $percentrounded . ' % . Unfortunately you have bunked ' . $bunksextra .' extra classes!';
    }
}              
?>
<tr>
    <td><?php echo $f1;?></td>
    <td><?php echo $f2;?></td> 
    <td><?php echo $f3;?></td>
    <td><?php echo $f4;?><form id="bunked" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"><input type="hidden" name="sub" value="<?php echo $f1;?>"><input type="submit" name="bunk" value="+"></form></td>
    <td><?php echo $f5; ?><form id="bunked" action="time.php" method="post"><input type="hidden" name="subject" value="<?php echo $f1;?>"><input type="submit" name="bunk" value="When did I bunk?"></form></td>
</tr>
<?php }?> 




</body>
</html>