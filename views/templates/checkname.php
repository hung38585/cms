<?php  
include_once  dirname(__DIR__,2)."\models\post.php";
$name = $_POST['name'];
$post = new post();
$post = $post->gettitle('template','name',htmlentities($name,ENT_QUOTES,"UTF-8"),'');
if (!$post) {
	echo 1;
}
?>