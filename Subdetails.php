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
</style>
</head>
<body>

<?php
$subname = $minreqd = $total = $bunked = $userno =  "";
$subnameErr = $minreqdErr = $totalErr = $bunkErr = "";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunkometer";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (!preg_match("/^[a-zA-Z0-9 ]*$/",$_POST["subname"])) {
       $subnameErr = "Special characters are not allowed"; 
     } else {
        $subname = $conn->real_escape_string($_POST["subname"]);
     }
     if ($_POST["minreqd"] > 100 ) {
        $minreqdErr = "Minimum attendance percentage cannot be greater than 100";
     } elseif ($_POST["minreqd"] < 0 ) {
        $minreqdErr = "Minimum attendance percentage cannot be negative";
     } else {
        $minreqd = $conn->real_escape_string($_POST["minreqd"]);
     }
     if ($_POST["total"] < 0 ) {
        $totalErr = "Minimum attendance percentage cannot be negative";
     } else {
        $total = $conn->real_escape_string($_POST["total"]);
     }
     if($_POST["bunked"] < 0 ) {
        $bunkErr = "No. of bunked classes cannot be negative."; 
     } elseif ( $_POST["bunked"] > $total ) {
        $bunkErr = "Bunked more classes than the total no. of classes? How?";
     } else {
        $bunked = $conn->real_escape_string($_POST["bunked"]);
     }
     $name = $_SESSION["name"];
}
$classno = "";
if($subname != "" && $minreqd != "" && $total != "" && $bunked != "" && $name !="") {
    if($bunked == 1) {
       $classno = "1";
    } elseif($bunked > 1) {
       $classno = "1 - ".$bunked;
    } elseif($bunked == 0) {
       $classno = "0";
    }
    if(strcmp($classno,"0")!=0) {
    $sql = "INSERT INTO time (Name, Subject, Classno) VALUES ('$name','$subname','$classno')";
    if ($conn->query($sql) === FALSE){
        echo "Error inserting record: " . $conn->error;
    }
    }
    $sql = "INSERT INTO bunkdetails (Name, Subjectname, Minreqd, Total, Bunked) VALUES ('$name','$subname',$minreqd,$total,$bunked)";
    if ($conn->query($sql) === TRUE) {
      echo "<script>window.location = 'Continue1.php'</script>";
    } else {
      echo "<script>alert('Error in values entered: Same subject cannot be entered twice')</script>";
    }
}
$conn->close();
?>  
    
<div id="header">
<h1>BUNK-O-METER</h1>
</div>
<p style="position:fixed;top:125px;left:50px;">You are logged in as <?php echo $_SESSION["name"];?></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" style="position:fixed;top:200px;left:100px;"> 
     Subject Name: <input type="text" name="subname" value="<?php echo $subname;?>" required>
     <span class="error">* <?php echo $subnameErr;?></span>
     <br><br>
     Minimum attendence percentage reqd.: <input type="number" name="minreqd" value="<?php echo $minreqd;?>" required>
     <span class="error">* <?php echo $minreqdErr;?></span>
     <br><br>
     Total no. of classes in the semester: <input type="number" name="total" value="<?php echo $total;?>" required>
     <span class="error">* <?php echo $totalErr;?></span>
     <br><br>
     No. of classes bunked already (Enter 0 if none): <input name="bunked" value="<?php echo $bunked;?>" required>
     <span class="error">* <?php echo $bunkErr;?></span>
     <br><br>
     <input type="submit" name="submit value="Submit">
</form>

<form method="post" action="logout.php" style="position:fixed;top:40px;right:50px;"> 
<input type="submit" value="Log Out" >
</form>

<form method="post" action="home.php" style="position:fixed;top:40px;right:150px;"> 
<input type="submit" value="Main Menu" >
</form>

</body>
</html>