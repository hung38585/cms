<?php 
include_once  dirname(__DIR__,2)."/models/post.php";
$post = new post();
$kind = $_POST['kind'];
$list = $post->getlistpage($kind);
$listpage = array();
foreach ($list as $key => $value) {
	$listpage[] = $value;
}
echo json_encode($listpage);
?>