<?php
include_once  dirname(__DIR__)."/models/path.php";
include_once  dirname(__DIR__)."/models/post.php";
include_once  dirname(__DIR__)."/models/template.php";
include_once  dirname(__DIR__)."/config/config.php";
class templatecontroller
{
	private $template;
	private $post;
	function __construct()
	{
		$this->template = new template();
		$this->post = new post();
	}
	public function index()
	{
		$where = array(); 
		$result = $this->post->list('template',$where,10,'','','','');
		return $result;
	}
	public function get_list_template($id)
	{
		$result = $this->template->list_template($id);
		return $result;
	}
	public function create()
	{
		$name = htmlspecialchars(addslashes($_POST['name']));
		$urlhtml = $_FILES["htmlupload"]["tmp_name"];
		$urlcs = $_FILES["csupload"]["tmp_name"];
		$namecs = $_FILES["csupload"]["name"];
		$result = $this->template->insert_template($name,$urlhtml,$urlcs,$namecs);
		if ($_POST['name'] && $_POST['urlhtml']) {
			$columns = array(
				"name"   => $name,
				"urltemplate" => "templates/".$name."/".$name.".html",
				"urlcs" => "templates/".$name,
				"category_id" => $_POST['category']
			);
			$kq = $this->post->insert('template',$columns);
			if ($kq) {
				header('Location: '.domain.'/template');
				exit;
			}else { 
				echo 'insert that bai ';
			}
		}	
	}
	public function detail($id)
	{
		$result = $this->post->one_record('template',$id);
		return $result;
	}
	public function delete()
	{
		if (isset($_POST['id'])) {
	      	foreach($_POST['id'] as $value) {
	      		$kq = $this->template->delete_template($value);
	        	$kq = $this->post->delete_post('template',$value);	 	        	
       		}	
       		if ($kq) {
       			header('Location: '.domain.'/template');
       		}
		}
	}
	public function getLevelUser()
	{
		$result = $this->post->get_leveluser($_SESSION['username']);
    	return $result;
	}
}
?>