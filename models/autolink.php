<?php
include_once  dirname(__DIR__)."/models/connectdb.php";
include_once  dirname(__DIR__)."/config/config.php";
class autolink
{
	private $dbconnect;
	function __construct()
	{
		$this->dbconnect = new connectdb();
		if ($this->dbconnect->checkDBConnection()) {
			return true;
		}else{
			return false;
		}	
	}
	public function get_userid($dept)
	{
		$sql = "SELECT id FROM user WHERE deptcode LIKE '".$dept."%'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
	}
	public function get_listpage_by_userid($userid)
	{
		$sql = "SELECT * FROM posts WHERE user_id='$userid'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;	
	}
}
?>