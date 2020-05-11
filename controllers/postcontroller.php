<?php 
include_once  dirname(__DIR__)."/models/post.php";
include_once  dirname(__DIR__)."/models/connectftp.php";
include_once  dirname(__DIR__)."/config/config.php";
include_once  dirname(__DIR__)."/models/path.php";
class postcontroller
{
	private $post;
	private $ftp;
	private $path;
	private $config;
	private $total_records;
	private $limit;
	function __construct()
	{
		$this->post = new post();
		$this->ftp = new FTP();
		$this->path = new path();
		if (isset($_GET['show'])) {
			$this->limit = $_GET['show'];
		}else{
			$this->limit = limit;
		}
		
	}
	public function logout()
	{
		if (isset($_SESSION['username'])){
    		unset($_SESSION['username']);
    	}
    }
	public function index()
	{
		$title = 'title';
		$search = '';
		$status = '';
		if (isset($_GET['search'])) {
			$search = $_GET['search'];
			$search = preg_replace('([\s]+)', ' ', $search);
			$search = htmlspecialchars($search,ENT_QUOTES);
		}
		if (isset($_GET['status'])) {
			$status = $_GET['status'];
		}
		switch ($status) {
			case status_1:
				$status = '1';
				break;
			case status_2:
				$status = '2';
				break;
			case status_3:
				$status = '3';
				break;	
			default:
				$status = '';
				break;
		}
		$level = $this->post->get_leveluser($_SESSION['username']);
		$where = array(); 
		if ($level == '2') {
			$user_id = $this->post->get_iduser($_SESSION['username']);
			$where = array("user_id" => $user_id); 
		}
		$condition_id = array();
		$condition_status = array();
		if ($level == '4') {
			$deptcode = $this->post->get_deptcode($_SESSION['username']);
			$listid = $this->post->get_list_userid_by_deptcode($deptcode);
			foreach ($listid as $key => $value) {
				$condition_id[] = $value; 
			}
			$tc = $this->post->get_tc($deptcode);
			switch ($tc) {
				case '3':
					$condition_status = array('7'); 
					break;
				case '2':
					$condition_status = array('6,7'); 
					break;
				case '1':
					$condition_status = array('5,6,7'); 
					break;	
				default:
					# code...
					break;
			}
		}else{
			if ($status) {
				$where += array("status" => $status); 
			}
		}
		$result = $this->post->list('posts',$where,$this->limit,$search,$title,$condition_id,$condition_status);
		return $result;

	}
	public function totalrecords()
	{
		$search = '';
		$status = '';
		$where = array();
		$condition_id = array();
		$condition_status = array();
		$level = $this->post->get_leveluser($_SESSION['username']);
		if ($level == '2') {
			$user_id = $this->post->get_iduser($_SESSION['username']);
			$where = array(
				"user_id" => $user_id
			);
		}
		if (isset($_GET['search'])) {
			$search = $_GET['search'];
		}
		if (isset($_GET['status'])) {
			$status = $_GET['status'];
			switch ($status) {
				case status_1:
					$status = '1';
					break;
				case status_2:
					$status = '2';
					break;
				case status_3:
					$status = '3';
					break;	
				default:
					$status = '';
					break;
			}
		}
		if ($level == '4') {
			$deptcode = $this->post->get_deptcode($_SESSION['username']);
			$listid = $this->post->get_list_userid_by_deptcode($deptcode);
			foreach ($listid as $key => $value) {
				$condition_id[] = $value; 
			}
			$tc = $this->post->get_tc($deptcode);
			switch ($tc) {
				case '3':
					$condition_status = array('7'); 
					break;
				case '2':
					$condition_status = array('6,7'); 
					break;
				case '1':
					$condition_status = array('5,6,7'); 
					break;	
				default:
					# code...
					break;
			}		
		}
		$search = htmlspecialchars($search,ENT_QUOTES);
		$result = $this->post->total_records('posts',$this->limit,$search,$status,$where,$condition_id,$condition_status);
		return $result;
	}
	public function create()
	{
		$status = '1';
		$level = $this->post->get_leveluser($_SESSION['username']);
		if ($level != '1') {
			$status = '7';
		}
		if ($_POST['txttitle'] && $_POST['txtcontent']) {
			$columns = array(
				"title"   => htmlspecialchars(addslashes($_POST['txttitle'])),
				"content" => ($_POST['txtcontent']),
				"status" => $status,
				"urlfolder" => htmlspecialchars(preg_replace('([\s]+)', ' ',$_POST['path'])),
				"template_id" => $_POST['templateid'],
				"ispostfb" => $_POST['isfb'],
				"user_id" =>$this->post->get_iduser($_SESSION['username'])
			);
			$kq = $this->post->insert('posts',$columns);
			if ($kq) {
				header('Location: '.domain.'/post');
			}else { 
				echo 'insert that bai ';
			}	
		}	
	}
	public function edit($id)
	{
		$ketqua = $this->post->one_record('posts',$id);
		return $ketqua;
	}
	public function update()
	{	
		if ($_POST['txttitle'] && $_POST['txtcontent']) {
			$id=$_POST['idpost'];
			$obj = $this->post->one_record('posts',$id);
			$status = $obj[3];
			$level = $this->post->get_leveluser($_SESSION['username']);
			if ($level == '1') {
				if ($status == "2" || $status == "3") {
					$status = "3";
				}else{
					$status = '1';
				}
			}
			if ($level == '2') {
				$status = '7';
			}
			$lastid =  $this->post->getlastid('versions');
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < 5; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    $randomString =substr($lastid.$randomString,0,6);
			$postrollback = array(
				"title" => $obj[1],
				"content" => $obj[2],
				"template_id" => $obj[5],
				"post_id" => $obj[0],
				"version" => $randomString
			);
			if ($obj[8]) {
				$postrollback = array(
					"title" => $obj[1],
					"content" => $obj[2],
					"template_id" => $obj[5],
					"post_id" => $obj[0],
					"published_at" => $obj[8],
					"version" => $randomString
				);
			}			
			$this->post->insert('versions',$postrollback);
			date_default_timezone_set('Asia/Ho_Chi_Minh');
			$columns = array(
				"title"   => htmlspecialchars($_POST['txttitle'],ENT_QUOTES),
				"content" => ($_POST['txtcontent']),
				"status" => $status,
				"template_id" => $_POST['templateid'],
				"updated_at" => date("Y-m-d h:i")
			);
			$kq = $this->post->update_post('posts',$id,$columns);
			if ($kq) {	
				header('Location: '.domain.'/post');
			}
		}	
	}
    public function deleteall()
    {
    	if (isset($_POST['id'])) {
	      	foreach($_POST['id'] as $value) {
	      		$recordpost = $this->post->one_record('posts',$value);
	        	$kq = $this->post->delete_post('posts',$value);
	      		if ($recordpost[3] != '1') {
	      			$this->ftp->delete($value);
	      			//get list related_page_id de public
	      			$listid = $this->post->get_list_relatedid_by($value);
	      			//get list autolink_id = id
	      			$autolink_id = $this->post->get_autolink_id($value);
	      			foreach ($autolink_id as $key => $autolinkid) {
	      				$this->post->delete_post('autolink', implode("",$autolinkid));
	      			}
	      			foreach ($listid as $key => $related_page_id) {
	      				$this->ftp->upload('posts',implode("",$related_page_id));
	      			}
	      		}	 	        	
       		}	
       		if ($kq) {
       			header('Location: '.domain.'/post');	
       		}
		}
    }
    //Upload file ftp
    public function up_load()
    {
    	if (isset($_POST['id'])) {
	      	foreach($_POST['id'] as $value) {
	        	$result = $this->ftp->upload('posts',$value); 
       		}
       		header('Location: '.domain.'/post');	
		}
    }
    //Unpublic page
    public function un_public()
    {
    	$id = $_POST['unpublic'];
    	$listid_autolink = $this->post->get_listid_by_related($id);
    	$listid = $this->post->get_list_relatedid_by($id);
		foreach ($listid_autolink as $key => $value) {
			$this->post->delete_post('autolink',implode("",$value));
		}
    	foreach ($listid as $key => $related_page_id) {
	      	$this->ftp->upload('posts',implode("",$related_page_id));
		}
		$kq = $this->ftp->delete($id);
		$column = array(
			"status" => 1
		);
		$this->post->update_post('posts',$id,$column);
       	header('Location: '.domain.'/post');	
    }
    public function get_list_html()
    {
		$result = $this->ftp->getlisthtml();
		return $result;     	
    }
    public function get_content($table,$id)
    {
    	$content = $this->post->getcontent($table,$id);
    	return $content;
    }
    public function get_urltemplate($id,$column)
    {
    	$result = $this->post->one_record('template',$id);
    	return $result[$column];
    }
    public function isautolink($template_id)
    {
    	$result = $this->post->one_record('template',$template_id);
  		$file = file_get_contents(dirname(__DIR__)."/".$result[2],'r+');
  		if (strrpos($file,'<!-- auto link -->')) {
  		 	return true;
  		}else{
  			return false;
  		} 	
    }
    public function getLevelUser()
    {
    	$result = $this->post->get_leveluser($_SESSION['username']);
    	return $result;
    }
    public function getIdUser()
    {
    	$result = $this->post->get_iduser($_SESSION['username']);
    	return $result;
    }
    public function approve()
    {
    	$deptcode = $this->post->get_deptcode($_SESSION['username']);
    	$tc = $this->post->get_tc($deptcode);
    	switch ($tc) {
    		case '3':
    			$status = '6';
    			break;
    		case '2':
    			$status = '5';
    			break;
    		case '1':
    			$status = '4';
    			break;	
    		default:
    			# code...
    			break;
    	}
    	$level = $this->post->get_leveluser($_SESSION['username']);
    	if ($level == '1') {
    		$status = '1';
    	}
    	$columns = array(
    		"status" => $status
    	);
    	$kq = $this->post->update_post('posts',$_POST['approve'],$columns);
		if ($kq) {	
			header('Location: '.domain.'/');
		}
    }
    public function denail()
    {
    	$columns = array(
    		"status" => '8'
    	);
    	$kq = $this->post->update_post('posts',$_POST['denail'],$columns);
		if ($kq) {	
			header('Location: '.domain.'/');
		}
    }
    public function getDeptcode()
    {
    	$deptcode = $this->post->get_deptcode($_SESSION['username']);
    	return $deptcode;
    }
    public function checkLink($url)
    {
    	$kq = true;
    	$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		$result = curl_exec($curl);
		if ($result !== false)
		{
		  	$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		  	//echo $statusCode."<br>";
		  	if (($statusCode > 200 && $statusCode < 300) || $statusCode >= 404)
		  	{
		    	$kq = false;
		  	}else{
		  		$kq = true;
		  	}
		}else
		{
		  	$kq = false;
		}
		return $kq;
    }
}
?>