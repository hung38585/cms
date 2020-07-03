<?php  
include_once  dirname(__DIR__,2)."/models/post.php";
$post = new post();
$id_user = $post->get_iduser($_SESSION['username']);
$id = $_POST['id'];
$check = $post->check_id_public($id,$id_user);
if ($check) {
	$result = 1;
}else{
	$result = 0;
}
echo $result;
?>