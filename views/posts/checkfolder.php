<?php  
include_once  dirname(__DIR__,2)."/models/post.php";
$post = new post();
$level = $post->get_leveluser($_SESSION['username']);
$folder = $_POST['folder'];
$dir = $_POST['dir'];
$dir = dirname(__DIR__,2)."/upload/".$dir;
$list_folder = scandir($dir);
$result = true;
foreach ($list_folder as $key => $value) {
	if ($folder == $value) {
		$result = false;
	}
}
if ($level != '1') {
	$result = false;
}
if ($result) {
	mkdir($dir.$folder);
}
echo $result;
?>