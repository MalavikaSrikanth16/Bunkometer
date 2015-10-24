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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>

function checkForm(buttonid) {
var a = buttonid.split("_");
reqdid = "subname_" + a[1];
var subject1 = document.getElementById(reqdid);
var subject = subject1.innerHTML;
var dataString = 'sub1=' + subject;
$.ajax({
type: "POST",
url: "bunkupdate.php",
data: dataString,
cache: false,
success: function() {
var ar = buttonid.split("_");
var subnameid = "subname_" + ar[1];
var minreqdid = "minreqd_" + ar[1];
var totalid = "total_" + ar[1];
var bunkedid = "bunk_" + ar[1];
var bunksleftid = "bunksleft_" + ar[1];
var subname = document.getElementById(subnameid);
var minreqd = document.getElementById(minreqdid);
var total = document.getElementById(totalid);
var bunked = document.getElementById(bunkedid);
var bunksleftelement = document.getElementById(bunksleftid);
bunkedvalue = parseInt(bunked.innerHTML) + 1;
bunked.innerHTML = "";
bunked.innerHTML = bunkedvalue;
var disp = bunksleftelement.innerHTML;
min = parseInt(minreqd.innerHTML);
tot = parseInt(total.innerHTML);
var maxbunk = ((100 - min)/100) * tot;
bunksleftdec = maxbunk - bunkedvalue;
bunksleft = parseInt(bunksleftdec);
if(bunksleft > 0) {
    if (bunksleft == 1) {
        disp = 'You can still bunk '+ bunksleft + ' class!'; 
    } else {
        disp = 'You can still bunk '+ bunksleft + ' classes!'; 
    }
} else if (bunksleft == 0) {
    var minpercent = minreqd.innerHTML;
    disp = 'Your attendance percentage is ' + minpercent + ' % . Bunk one more class and you will have crossed the limit. Be careful!';
} else {
    var bunksextra = -bunksleft;
    var percent = ((tot - bunkedvalue)/tot) * 100;
    if(bunksextra == 1) {
        disp = 'Your attendance percentage has dropped below the minimum and is now ' + percent + ' % . Unfortunately you have bunked ' + bunksextra +' extra class!';
    } else {
        disp = 'Your attendance percentage has dropped below the minimum and is now ' + percent + ' % . Unfortunately you have bunked ' + bunksextra +' extra classes!';
    }
} 
bunksleftelement.innerHTML = disp;
}
});
return false;
}
</script>
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
$n = 1;
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunkometer";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$name = $_SESSION["name"];

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
    <td id="subname_<?php echo $n;?>"><?php echo $f1;?></td>
    <td id="minreqd_<?php echo $n;?>"><?php echo $f2;?></td> 
    <td id="total_<?php echo $n;?>"><?php echo $f3;?></td>
    <td><span id="bunk_<?php echo $n;?>"><?php echo $f4;?></span><span id="btn"><form id="bunked" name="bunked"><input type="button" id="button_<?php echo $n;?>" name="bunk" value="+" onclick="checkForm(this.id)" onmouseover="updatedisp(this.id)"></form></span></td>
    <td ><span id="bunksleft_<?php echo $n;?>"><?php echo $f5;?></span><span id="btn"><form id="bunked" action="time.php" method="post"><input type="hidden" name="subject" value="<?php echo $f1;?>"><input type="submit" name="bunk" value="When did I bunk?"></form></span></td>
<?php $n++;}?> 


</body>
</html>