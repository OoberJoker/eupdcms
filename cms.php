<html> 
<head> 
<title>Sign-In</title> 
<link rel="stylesheet" type="text/css" href="style-sign.css"> </head> 
<body id="body-color"> <div id="Sign-In"> 
<fieldset style="width:30%"><legend>LOG-IN HERE</legend> 
<form method="POST" action="#"> 
User <br><input type="text" name="user" size="40"><br> 
Password <br><input type="password" name="pass" size="40"><br> 
<input id="button" type="submit" name="submit" value="Log-In"> </form> </fieldset> </div> </body> </html> 



<?php 
define('DB_HOST','localhost'); 
define('DB_NAME','cms'); 
define('DB_USER','root'); 
define('DB_PASSWORD','jwalking99'); 
//$con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error()); 
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//starting the session for user profile page 
if(!empty($_POST['user'])) //checking the 'user' name which is from Sign-In.html, is it empty or have some text 
{ 
var_dump($_POST);
	$query = mysqli_query($con,"SELECT * FROM UserName where userName = '$_POST[user]' AND pass = '$_POST[pass]'") 
		or 
	die("dead"); 
var_dump($query);
$row = mysqli_free_result($query) or die("umm?"); 
var_dump($row);
if(!empty($row['userName']) AND !empty($row['pass'])) { 
	$_SESSION['userName'] = $row['pass']; echo "SUCCESSFULLY LOGIN TO USER PROFILE PAGE..."; 
} 
else { 
	echo "SORRY... YOU ENTERD WRONG ID AND PASSWORD... PLEASE RETRY..."; 
} 
} 
 
if(isset($_POST['submit'])) { 
	SignIn(); 
} 
?>

