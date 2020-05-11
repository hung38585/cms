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
}
?>