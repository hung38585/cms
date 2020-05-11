<?php 
include  dirname(__DIR__,2)."\config\config.php";
	$username = htmlentities($_POST['username'],ENT_QUOTES);
	$password = htmlentities($_POST['password'],ENT_QUOTES);
	if ($username == '' or $password == '') {
		echo "1";
	}else{
		$sql ="SELECT username FROM user WHERE username='$username' AND password='$password'";
		$connection = mysqli_connect(host_name, user_name, db_password, db_name);
	    $result = mysqli_query($connection, $sql);
	    $row = mysqli_fetch_row($result);
	    if ($row) {
	    	$_SESSION['username'] = $username;
	        echo "2";
	    }else{
	    	echo "3";
	    }
	}
	
?>