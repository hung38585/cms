<?php  
include_once  dirname(__DIR__)."/models/post.php";
include_once  dirname(__DIR__)."/models/organization.php";
include_once  dirname(__DIR__)."/config/config.php";
class organizationcontroller
{
	private $post;
	private $organization;
	function __construct()
	{
		$this->post = new post();
		$this->organization = new organization();
	}
	public function index()
	{
		$where = array(); 
		$result = $this->post->list('organizations',$where,20,'','','','');
		return $result;
	}
	public function create()
	{
		$template_id = '';
 		foreach ($_POST['template_id'] as $key => $value) {
 			$template_id .= ','.$value;
 		}
 		$template_id = substr($template_id,1,strlen($template_id)-1);
		$colums = array(
			"name" => htmlspecialchars(addslashes($_POST['name'])),
			"deptcode" =>  $_POST['deptcode'],
			"template_id" => $template_id,
			"folder" => htmlspecialchars(addslashes($_POST['folder']))
		);
		$kq = $this->post->insert('organizations',$colums);
		if ($kq) {
			header('Location: '.domain.'/organization');
		}
	}
	public function delete()
	{
		if (isset($_POST['id'])) {
	      	foreach($_POST['id'] as $value) {
	        	$kq = $this->post->delete_post('organizations',$value);	 	        	
       		}	
       		if ($kq) {
       			header('Location: '.domain.'/organization');
       		}
		}
	}
	public function getListOrganc1()
	{
		$where = array(); 
		$result = $this->organization->get_list_organc1();
		return $result;
	}
	public function getListOrganc2($dept)
	{
		$result = $this->organization->get_list_organc2($dept);
		return $result;		
	}
	public function getListOrganc3($dept)
	{
		$result = $this->organization->get_list_organc3($dept);
		return $result;		
	}
	public function checkName($name)
	{
		$result = $this->organization->checkname($name);
		return $result;
	}
	public function getTemplateId($deptcode)
	{
		$result = $this->organization->get_templateid($deptcode);
		return $result;
	}
	public function getFolder($deptcode)
	{
		$result = $this->organization->get_folder($deptcode);
		return $result;
	}
}
?>