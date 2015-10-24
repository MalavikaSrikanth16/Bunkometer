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

<div id="header">
<h1>BUNK-O-METER</h1>
</div>

<p style="position:fixed;top:125px;left:50px;">You are logged in as <?php echo $_SESSION["name"];?></p>

<p style="position:fixed;top:200px;left:100px;">Do you want to enter details of another subject?</p>

<input type="button" value="YES" onclick="location.href='Subdetails.php'" style="position:fixed;top:250px;left:100px;">
<input type="button" value="NO" onclick="location.href='Bunkometer2.php'" style="position:fixed;top:250px;left:150px;">

<form method="post" action="logout.php" style="position:fixed;top:40px;right:50px;"> 
<input type="submit" value="Log Out" >
</form>

</body>
</html>