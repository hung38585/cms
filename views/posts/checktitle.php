<?php  
include_once  dirname(__DIR__,2)."\models\post.php";
$post = new post();
$title = $_POST['title'];
if (isset($_POST['idpost'])) {
	$idpost = $_POST['idpost'];
	$listtitle = $post->gettitle('posts','title',htmlspecialchars(addslashes($title)),$idpost);
}else{
	$listtitle = $post->gettitle('posts','title',htmlspecialchars(addslashes($title)),'');
}
if ($listtitle) {
	echo 1;
}else{
	echo 2;
}
?>