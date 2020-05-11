<?php
include  dirname(__DIR__,2)."\config\config.php";
include  dirname(__DIR__,2)."\models\path.php";
$path = new path();
$filename = $_POST['filename'];
$sql ="SELECT urlfolder FROM posts";
$connection = mysqli_connect(host_name, user_name, db_password, db_name);
$result = mysqli_query($connection, $sql);
$list = array();
while ($row = mysqli_fetch_row($result)) {
	$list[]= $row;
}
$list = array_unique($list,0);
unset($list[0]);
$result = 1;
foreach ($list as $key => $value) {
	$folder = $path->getfolder(implode("",$value));
	if ($filename == array_pop($folder)) {
		$result = 2;
	}
}
if ($filename == 'index') {
	$result = 2;
}
echo $result;
?>