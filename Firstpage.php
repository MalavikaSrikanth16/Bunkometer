<?php
session_start();
if(isset($_SESSION["name"])) {
  echo "<script>window.location = 'Bunkometer2.php'</script>";
} else {
  echo "<script>window.location = 'Bunkometer1.php'</script>";
}
?>
