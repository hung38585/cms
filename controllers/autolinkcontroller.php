<?php
include_once  dirname(__DIR__)."/models/post.php";
include_once  dirname(__DIR__)."/config/config.php";
class autolinkcontroller
{
	private $post;
	function __construct()
	{
		$this->post = new post();
	}
	public function index()
	{
		$where = array(); 
		$result = $this->post->list('autolink',$where,10,'','','','');
		return $result;
	}
	public function delete()
	{
		if (isset($_POST['id'])) {
	      	foreach($_POST['id'] as $value) {
	        	$kq = $this->post->delete_post('autolink',$value);	 	        	
       		}	
       		if ($kq) {
       			header('Location: '.domain.'/autolink');
       		}
		}
	}
}
?>