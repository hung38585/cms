<?php  
	include_once  dirname(__DIR__,2)."\models\path.php";
	$path = new path();
	$folder = $_POST['folder'];
	$dir = dirname(__DIR__,2)."/upload/";
	$path = $path->getlistfolder($dir.$folder);
	unset($path[0]);
	unset($path[1]);
	$content = '<p id="first" style="margin: 0;"></p>';
	//echo json_encode($path);
	if ($path) {
		foreach ($path as $key => $value) {
			if (!strpos($value,'.html')) {
				$name = "'".$value."'";
				$content .= '<button onclick="selectfolder('.$name.')" class="btn col-md-3" value="'.$value.'" style=" padding: 0;"><h6><i class="fas fa-folder" style="font-size:32px"></i> <br>'.$value.'</h6></button>';
			}else{
				$content .= '<button class="btn col-md-3" value="'.$value.'" style=" padding: 0; "><h6><i class="fab fa-internet-explorer" style="font-size:30px"></i> <br>'.$value.'</h6></button>';	
			}
		}
	}else{
		$content.='<p class="text-secondary empty">Folder is empty!</p>';
	}
	echo $content;
?>