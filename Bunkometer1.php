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
#login {
    height:50px;
    width:100px;
    position:absolute;
    top:250px;
    left:100px;    	      
}
#text_or {
    position:fixed;
    top:250px;
    left:275px;
}
#signup {
    height:50px;
    width:100px;
    position:fixed;
    top:250px;
    left:375px;
}
.regform {
   position:fixed;
   top:320px;
   left:375px;
}
</style>
<script>
function checkForm() {
var name = document.getElementById("name1").value;
var password = document.getElementById("pswsnup1").value;
var repassword = document.getElementById("repswsnup1").value;
if (name == '' || password == '' || repassword == '') {
  alert("Fill All Fields");
} else {
  var name1 = document.getElementById("name");
  var password1 = document.getElementById("pswsnup");
  var repassword1 = document.getElementById("repswsnup");
  if (name1.innerHTML == 'Name has already been entered' || repassword1.innerHTML == 'Password is not the same. Please check what you have typed' || repassword1.innerHTML =='No. of characters is not the same' ) {
    alert("Fill Valid Information");
  } else {
    document.getElementById("signupform").submit();
  }
}
}
function validate(field, query) {
var xmlhttp;
if (window.XMLHttpRequest) { 
xmlhttp = new XMLHttpRequest();
} else { 
xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
document.getElementById(field).innerHTML = xmlhttp.responseText;
}
}
xmlhttp.open("GET", "validation.php?field=" + field + "&query=" + query, true);
xmlhttp.send();
}
</script>
</head>
<body>

<?php
$pswlogin = $namelgn = "";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunkometer";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$counter = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$namelgn = $conn->real_escape_string($_POST["namelgn"]);
$pswlogin = $conn->real_escape_string($_POST["pswlogin"]);
$hash = sha1($pswlogin);
if($namelgn != "" && $pswlogin != "" ) {
   $sql = "SELECT * FROM userinfo";
   $result = $conn->query($sql);
    if($result -> num_rows > 0) {  
         while($row = $result->fetch_assoc()) { 
            if(strcmp($row["Name"],$namelgn)==0 and strcmp($hash,$row["password"])==0) {
               $counter =  1;
               $_SESSION["name"] = $namelgn;
               echo "<script>window.location = 'Bunkometer2.php'</script>";
             }
         }  
         if($counter == 0) {
            echo "<script> alert('Invalid Name or Password. Please login again'); </script>";
         }  
     } 
} else {
    echo "<script>alert('Name and Password are required. Please login again.');</script>"; 
}  
}   
$conn->close();
?>

<div id="header">
<h1>BUNK-O-METER</h1>
</div>

<div class="options">
<input id="login" type="button" value="Log In" onclick = "loginform()" >
<p id="text_or">OR</p>
<input id="signup" type="button" value="Sign Up" onclick = "signupform()">
</div>

<div id="logform" style="position:fixed;top:320px;left:100px;"></div>

<script type="text/javascript"> 
function signupform() {
var d = document.createElement("div");
d.setAttribute("id","regform");
d.setAttribute("class","regform");
document.body.appendChild(d);

var x = document.getElementById("regform");
var createform = document.createElement('form');
createform.setAttribute("action", "submit.php"); 
createform.setAttribute("id", "signupform");
createform.setAttribute("method", "post");
x.appendChild(createform);

var t = document.createTextNode("Name: ");       
createform.appendChild(t);
var nmelement  = document.createElement("INPUT");
nmelement.setAttribute("type", "text");
nmelement.setAttribute("id", "name1");
nmelement.setAttribute("name", "name");
nmelement.setAttribute("pattern", "[ A-Za-z]{1,}");
nmelement.setAttribute("title", "Must contain only letters and white spaces");
nmelement.setAttribute("onblur", "validate('name',this.value)");
createform.appendChild(nmelement);
var d1 = document.createElement("div"); 
d1.setAttribute("id", "name");     
createform.appendChild(d1);

var linebreak1 = document.createElement('br');
var linebreak2 = document.createElement('br');
createform.appendChild(linebreak1);
createform.appendChild(linebreak2);

var p = document.createTextNode("Password: ");       
createform.appendChild(p);
var pswelement = document.createElement("INPUT"); 
pswelement.setAttribute("type", "password");
pswelement.setAttribute("id", "pswsnup1");
pswelement.setAttribute("name", "pswsnup");
pswelement.setAttribute("onblur", "validate('pswsnup',this.value)");
createform.appendChild(pswelement);
var d2 = document.createElement("div"); 
d2.setAttribute("id", "pswsnup");     
createform.appendChild(d2);

var linebreak3 = document.createElement('br');
var linebreak4 = document.createElement('br');
createform.appendChild(linebreak3);
createform.appendChild(linebreak4);

var p1 = document.createTextNode("ReEnter Password: ");       
createform.appendChild(p1);
var psw1element = document.createElement("INPUT"); 
psw1element.setAttribute("type", "password");
psw1element.setAttribute("id", "repswsnup1");
psw1element.setAttribute("name", "repswsnup");
psw1element.setAttribute("oninput", "validate('repswsnup',this.value)");
createform.appendChild(psw1element);
var d3 = document.createElement("div"); 
d3.setAttribute("id", "repswsnup");     
createform.appendChild(d3);

var linebreak5 = document.createElement('br');
var linebreak6 = document.createElement('br');
createform.appendChild(linebreak5);
createform.appendChild(linebreak6);

var submitelement = document.createElement("INPUT"); 
submitelement.setAttribute("type", "button");
submitelement.setAttribute("value", "Submit");
submitelement.setAttribute("name", "signup-sbmt");
submitelement.setAttribute("onclick", "checkForm()");
createform.appendChild(submitelement);
}

function loginform() {
var y = document.getElementById("logform");
var createform2 = document.createElement('form'); 
createform2.setAttribute("action", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"); 
createform2.setAttribute("method", "post"); 
y.appendChild(createform2);

var u = document.createTextNode("Name: ");       
createform2.appendChild(u);
var idelement  = document.createElement("INPUT"); 
idelement.setAttribute("type", "text");
idelement.setAttribute("name", "namelgn");
idelement.setAttribute("pattern", "[ A-Za-z]{1,}");
idelement.setAttribute("title", "Must contain only letters and white spaces");
idelement.setAttribute("value", "<?php echo $namelgn;?>");
createform2.appendChild(idelement);

var linebreak1 = document.createElement('br');
var linebreak2 = document.createElement('br');
createform2.appendChild(linebreak1);
createform2.appendChild(linebreak2);

var pw = document.createTextNode("Password: ");       
createform2.appendChild(pw);
var pwelement = document.createElement("INPUT"); 
pwelement.setAttribute("type", "password");
pwelement.setAttribute("name", "pswlogin");
pwelement.setAttribute("value", "<?php echo $pswlogin;?>");
createform2.appendChild(pwelement);

var linebreak3 = document.createElement('br');
var linebreak4 = document.createElement('br');
createform2.appendChild(linebreak3);
createform2.appendChild(linebreak4);

var submitelement = document.createElement("INPUT"); 
submitelement.setAttribute("type", "submit");
submitelement.setAttribute("value", "Submit");
submitelement.setAttribute("name", "login-sbmt");
createform2.appendChild(submitelement);
}
</script>
</body>
</html>