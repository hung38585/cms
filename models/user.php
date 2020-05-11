<?php
include_once  dirname(__DIR__)."/models/connectdb.php";
include_once  dirname(__DIR__)."/config/config.php";
class user
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
	public function checkUsername($username)
	{
		$sql = "SELECT id FROM user WHERE username='$username'";
		$result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
	}
	public function exportToCSV()
    {
        header('Content-Type: text/csv; charset=utf-8');  
        header('Content-Disposition: attachment; filename=list.csv'); 
        ob_end_clean () ;
        $output = fopen("php://output", "w+"); 
        fputcsv($output, array('ID', 'username', 'password', 'level', 'deptcode','created_at')); 
        $sql = "SELECT * FROM user";
        $result = $this->dbconnect->connectQuery($sql);
        while($row = mysqli_fetch_assoc($result))  
        {  
            fputcsv($output, $row);  
        } 
        fclose($output);
    }
}
?>