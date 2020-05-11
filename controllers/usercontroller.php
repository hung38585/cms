<?php
include_once  dirname(__DIR__)."/models/post.php";
include_once  dirname(__DIR__)."/models/user.php";
include_once  dirname(__DIR__)."/config/config.php";
class usercontroller
{
	private $post;
	function __construct()
	{
		$this->post = new post();
		$this->user = new user();
	}
	public function index()
	{
		$where = array(); 
		$result = $this->post->list('user',$where,15,'','','','');
		return $result;
	}
	public function create()
	{
		$colums = array(
			"username" => htmlspecialchars(addslashes($_POST['username'])),
			"password" =>  $_POST['password'],
			"level" => $_POST['level'],
			"deptcode" => $_POST['deptcode']
		);
		$kq = $this->post->insert('user',$colums);
		if ($kq) {
			header('Location: '.domain.'/user');
		}
	}
	public function delete()
	{
		if (isset($_POST['id'])) {
	      	foreach($_POST['id'] as $value) {
	        	$kq = $this->post->delete_post('user',$value);	 	        	
       		}	
       		if ($kq) {
       			header('Location: '.domain.'/user');
       		}
		}
	}
	public function getLevelUser()
	{
		$result = $this->post->get_leveluser($_SESSION['username']);
    	return $result;
	}
    public function exportCSV()
    {
    	$result = $this->user->exportToCSV();
    }
    public function importCSV()
    {
    	$file = $_FILES["csv"]["tmp_name"];
    	$handle = fopen($file, 'r');
    	$handle2 = fopen($file, 'r');
    	$result='';
		while ($data= fgetcsv($handle, 1000, ","))
		{
			list($id,$username, $password, $level, $deptcode) = $data;
			//echo $id.' '.$username . ' ' . $password . ' ' . $level.' '.$deptcode.'<br>';
			if (!$username || !$password || !$level) {
				$result .= '<br>'.$id.': ';
			}else{
				if ($level != '1' && $level != '3') {
					if (!$deptcode) {
						$result .= '<br>'.$id.': ';
					}
				}
			}
			if (!$username) {
				$result .= 'Username is empty. ';
			}
			if (!$password) {
				$result .= 'Password is empty. ';
			}
			if (!$level) {
				$result .= 'Level is empty. ';
				if (!$deptcode) {
					$result .= 'Deptcode is empty. ';
				}
			}else{
				if ($level != '1' && $level != '3') {
					if (!$deptcode) {
						$result .= 'Deptcode is empty. ';
					}
				}
			}
		}
		if ($result) {
			echo '<br><a href="/user" class="btn btn-danger ml-1">Back</a>';
			echo $result;
			exit();
		}else{
			$this->post->delete_all('user');
			$i = 0;
			while ($data= fgetcsv($handle2, 1000, ","))
			{
				list($id,$username, $password, $level, $deptcode) = $data;
				if ($i > 0) {
					$colums = array(
						"username" => htmlspecialchars(addslashes($username)),
						"password" => htmlspecialchars($password),
						"level" => htmlspecialchars($level),
						"deptcode" => htmlspecialchars($deptcode)
					);
					$this->post->insert('user',$colums);
				}
				$i++;
			}
			header('Location: '.domain.'/user');	
		}
		fclose($handle);	
    }
}
?>