<?php  
include_once  dirname(__DIR__,2)."/models/user.php";
$user = new user();
$username = $_POST['username'];
if ($user->checkUsername($username)) {
	echo 2;	
}else{
	echo 1;
}
?>