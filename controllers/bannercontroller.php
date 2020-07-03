<?php
include_once  dirname(__DIR__)."/models/post.php";
include_once  dirname(__DIR__)."/config/config.php";
class bannercontroller
{
	private $post;
	function __construct()
	{
		$this->post = new post();
	}
	public function index()
	{
		$where = array(); 
		$result = $this->post->list('banners',$where,100,'','','','');
		return $result;
	}
	public function create()
	{ 
		$dir = dirname(__DIR__)."/assets/images/";
		$urlimage = $_FILES["image"]["tmp_name"]; 
		$imagename = $_FILES["image"]["name"];
		date_default_timezone_set('Asia/Ho_Chi_Minh'); 
		$colums = array(
			"title" => htmlspecialchars(addslashes($_POST['title'])),
			"width" =>  $_POST['width'],
			"height" => $_POST['height'],
			"ara_id" => $_POST['ara_id'],
			"image" => $imagename,
			"created_at" => date("Y-m-d H:i:s")
		);  
		$kq = $this->post->insert('banners',$colums);
		//move image 
		move_uploaded_file($urlimage, $dir.$imagename); 
		if ($kq) {
			header('Location: '.domain.'/banner');
		}
	}
	public function edit($id)
	{
		$ketqua = $this->post->one_record('banners',$id);
		return $ketqua;
	}
	public function update($id)
	{
		$imagename = $_POST['image'];
		if ($_FILES["filename"]['name']) {
			$dir = dirname(__DIR__)."/assets/images/";
			$urlimage = $_FILES["filename"]["tmp_name"]; 
			$imagename = $_FILES["filename"]["name"];
			//move image 
			move_uploaded_file($urlimage, $dir.$imagename); 
		} 
		date_default_timezone_set('Asia/Ho_Chi_Minh'); 
		$colums = array(
			"title" => htmlspecialchars(addslashes($_POST['title'])),
			"width" =>  $_POST['width'],
			"height" => $_POST['height'],
			"ara_id" => $_POST['ara_id'],
			"image" => $imagename, 
		); 
		$kq = $this->post->update_post('banners',$id,$colums);  
		if ($kq) {
			header('Location: '.domain.'/banner');
		} 
	}
	public function delete()
	{
		if (isset($_POST['id'])) {
	      	foreach($_POST['id'] as $value) {
	        	$kq = $this->post->delete_post('banner',$value);	 	        	
       		}	
       		if ($kq) {
       			header('Location: '.domain.'/banner');
       		}
		}
	}
}
?>