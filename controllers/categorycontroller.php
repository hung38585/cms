<?php
include_once  dirname(__DIR__)."/models/post.php";
include_once  dirname(__DIR__)."/config/config.php";
class categorycontroller
{
	private $post;
	function __construct()
	{
		$this->post = new post();
	}
	public function index()
	{
		$where = array(); 
		$result = $this->post->list('categories',$where,100,'','','','');
		return $result;
	} 
	public function create()
	{
		$colums = array(
			"name" => htmlspecialchars(addslashes($_POST['name'])),
		);
		$kq = $this->post->insert('categories',$colums);
		if ($kq) {
			header('Location: '.domain.'/category');
		}
	}
}
?>