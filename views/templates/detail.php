<?php  
include_once dirname(__DIR__,2)."/controllers//templatecontroller.php";
include_once  dirname(__DIR__,2)."/models/path.php";
if (!isset($_SESSION['username'])){
  header('Location: '.domain.'/views/posts/login.php');
}
?>
<title>Detail Template</title>
<?php
if (isset($_SESSION['username'])) {
	$temp = new templatecontroller();
	$path = new path();
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $link_arr = explode('/', $link);
    $id = $link_arr[count($link_arr)-1];
	$temp = $temp->detail($id);
	if($temp[3]){
        $listfolder = scandir(dirname(__DIR__,2)."/".$temp[3]);
        if(!$path->checkfolder($listfolder,'css')){
            $listcss = scandir(dirname(__DIR__,2)."/".$temp[3]."/css");
            unset($listcss[0]);
            unset($listcss[1]);
            foreach($listcss as $value){
                echo '<link rel="stylesheet" type="text/css" href="../../'.$temp[3].'/css/'.$value.'">';
            }
        }
        if(!$path->checkfolder($listfolder,'js')){
            $listjs = scandir(dirname(__DIR__,2)."/".$temp[3]."/js");
            unset($listjs[0]);
            unset($listjs[1]);
            foreach($listjs as $value){
                echo '<script src=""../../'.$temp[3].'/js/'.$value.'"></script>';
            }
        }
	}    
    $file = file_get_contents(dirname(__DIR__,2)."//".$temp[2],'r+');
	//$doc = new DOMDocument();
	//libxml_use_internal_errors(true);
	//$doc->loadHTML($file);
	echo $file;
	//include dirname(__DIR__,2)."/".$temp[2];
}
?>