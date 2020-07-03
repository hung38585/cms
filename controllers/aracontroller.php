<?php
include_once  dirname(__DIR__)."/models/post.php";
include_once  dirname(__DIR__)."/config/config.php";
class aracontroller
{
	private $post;
	function __construct()
	{
		$this->post = new post();
	}
	public function index()
	{
		$where = array(); 
		$result = $this->post->list('aras',$where,100,'','','','');
		return $result;
	}
	public function create()
	{
		date_default_timezone_set('Asia/Ho_Chi_Minh');  
		$colums = array(
			"name" => htmlspecialchars(addslashes($_POST['name'])),
			"width" =>  $_POST['width'],
			"height" => $_POST['height'],
			"style" => $_POST['style'],
			"quantity" => $_POST['quantity'],
			"created_at" => date("Y-m-d H:i:s")
		); 
		$kq = $this->post->insert('aras',$colums);
		if ($kq) {
			header('Location: '.domain.'/ara');
		}
	}
	public function edit($id)
	{
		$result = $this->post->one_record('aras',$id);
		return $result;
	}
	public function update($id)
	{ 
		$colums = array(
			"name" => htmlspecialchars(addslashes($_POST['name'])),
			"width" =>  $_POST['width'],
			"height" => $_POST['height'],
			"style" => $_POST['style'],
			"quantity" => $_POST['quantity'], 
		); 
		$kq = $this->post->update_post('aras',$id,$colums);  
		if ($kq) {
			header('Location: '.domain.'/ara');
		} 
	}
	public function delete()
	{
		if (isset($_POST['id'])) {
	      	foreach($_POST['id'] as $value) {
	        	$kq = $this->post->delete_post('aras',$value);	 	        	
       		}	
       		if ($kq) {
       			header('Location: '.domain.'/ara');
       		}
		}
	}
	public function getListBanner($id)
	{
		$where = array('ara_id' => $id); 
		$result = $this->post->list('banners',$where,100,'','','','');
		return $result;
	}
}
?>