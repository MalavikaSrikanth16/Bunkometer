<?php 
$name = $pswsnup = $pswsnup2 = "";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}  

$name = $conn->real_escape_string($_POST["name"]);
$pswsnup = $conn->real_escape_string($_POST["pswsnup"]);
$pswsnup2 = sha1($pswsnup);
if( $pswsnup != "" && $name != "" ) {
  $sql = "INSERT INTO userinfo (Name,Password) VALUES ('$name','$pswsnup2')"; 
  if ($conn->query($sql) === FALSE) {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
echo "<script>location.href='Shop1.php'</script>";
?>