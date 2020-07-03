<?php  
include_once  dirname(__DIR__)."/models/post.php"; 
include_once  dirname(__DIR__)."/models/feedback.php";
include_once  dirname(__DIR__)."/config/config.php";
class feedbackcontroller{
	private $post;
	private $feedback;
	function __construct()
	{
		$this->post = new post(); 
		$this->feedback = new feedback(); 
	}
	public function index()
	{
		$where = array(); 
		$result = $this->feedback->list_page_feedback();
		return $result;
	}
}
?>