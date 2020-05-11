<?php  
include  dirname(__DIR__,2)."\controllers\postcontroller.php";
$id = $_POST['id'];
$template = new postcontroller();
$template = $template->get_urltemplate($id,2);
echo $template; 
?>