<?php
class organization
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
	public function get_list_organc1()
	{
		$sql = "SELECT * FROM organizations WHERE deptcode LIKE '%000000'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
	}
	public function get_list_organc2($dept)
	{
		$sql = "SELECT * FROM organizations WHERE deptcode LIKE '".$dept."%000' AND deptcode !='".$dept."000000'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
	}
	public function get_list_organc3($dept)
	{
		$sql = "SELECT * FROM organizations WHERE deptcode LIKE '".$dept."%' AND deptcode !='".$dept."000'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
	}
	public function checkname($name)
	{
		$sql = "SELECT id FROM organizations WHERE deptcode='$name'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
	}
	public function get_templateid($deptcode)
	{
		$sql = "SELECT template_id FROM organizations WHERE deptcode='$deptcode'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
	}
	public function get_folder($deptcode)
	{
		$sql = "SELECT folder FROM organizations WHERE deptcode='$deptcode'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
	}
}	
?>