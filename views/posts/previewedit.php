<?php  
$content = $_POST['content'];
$temp = $_POST['temp'];
$file = file_get_contents(dirname(__DIR__,2)."/".$temp,'r+');
$file = str_replace('<p class="formcreatepost"></p>',$content,$file);
echo $file;
?>