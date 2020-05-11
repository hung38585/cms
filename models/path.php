<?php  
	include_once  dirname(__DIR__)."\config\config.php";
	class path
	{
		public function getfolder($url)
		{
			return explode('/', $url);
		}
		public function getname($url)
		{
			$d = count(explode('/', $url));
			return ($d-1);
		}
		public function setcontent($title,$content)
		{
			$result = '<!DOCTYPE html>
		<html>
		<head>
		<meta charset="utf-8">
		<title>'.$title.'</title>
		</head>
		<body>
			'.$content.'
		</body>
		</html>';
			return $result;
		}
		public function getlistfolder($dir)
		{
			$folder = scandir($dir);
			return $folder;
		}
		public function checkfolder($list=array(),$name)
		{
			$result = true;
			foreach ($list as $key => $value) {
				if ($value == $name) {
					$result = false;
				}
			}
			return $result;
		}
		public function getlinkpub($url)
		{
			$arr = explode('/',$url);
			return pubdomain."/".$url."/".$arr[count($arr)-1].".html";
		}
	}
?>