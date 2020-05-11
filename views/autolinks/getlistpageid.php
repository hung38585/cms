<?php 
include_once dirname(__DIR__,2)."/models/autolink.php"; 
include_once  dirname(__DIR__,2)."/models/post.php"; 
$autolink = new autolink();
$post = new post();
$id = $_POST['id'];
$list_related_page = $post->get_list_page_public($id);
$list_related_page = explode(',', implode('', $list_related_page[0]));
echo json_encode($list_related_page);
?>