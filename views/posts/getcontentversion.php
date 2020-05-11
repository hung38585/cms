<?php  
include_once dirname(__DIR__,2)."/controllers/postcontroller.php";
include_once  dirname(__DIR__,2)."/models/post.php";
include_once  dirname(__DIR__,2)."/models/path.php";
$content = new postcontroller();
$template = new post();
$path = new path();
$id = $_POST['id'];
$template_id = $template->one_record('postrollback',$id);
$template = $content->get_urltemplate($template_id[3],2);
$urlcs = $content->get_urltemplate($template_id[3],3);
$file = file_get_contents(dirname(__DIR__,2)."/".$template,'r+');
$content = $content->get_content('postrollback',$id);
$listfolder = scandir(dirname(__DIR__,2)."/".$urlcs);
if ($urlcs) {
	if(!$path->checkfolder($listfolder,'css')){
	    $listcss = scandir(dirname(__DIR__,2)."/".$urlcs."/css");
	    unset($listcss[0]);
	    unset($listcss[1]);
	    foreach($listcss as $value){
	        echo '<link rel="stylesheet" type="text/css" href="../../'.$urlcs.'/css/'.$value.'">';
	    }
	}	
}
$newcontent = str_replace('<p class="formcreatepost"></p>',$content,$file);
echo $newcontent;
?>