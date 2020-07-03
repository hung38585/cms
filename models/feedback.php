<?php
include_once  dirname(__DIR__)."/models/connectdb.php";
include_once  dirname(__DIR__)."/config/config.php";
class feedback
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
	public function list_page_feedback()
	{
		$sql = "SELECT DISTINCT page_id FROM feedbacks";
		$list = $this->dbconnect->connectQuery($sql);
		$result = array();
        if ($list){
            while($row = mysqli_fetch_assoc($list)){
                $result[] = $row;
            }
        }
        return $result;
	}
	public function checkfeedback($page_id,$question,$answer)
    {
        $sql = "SELECT id FROM feedbacks WHERE page_id='$page_id' AND question='$question' AND answer='$answer'";
        $result = $this->dbconnect->connectQuery($sql);
        $id = false;
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
        } 
        return $id;
    }
    public function getfeedback($id)
    {
    	$sql = "SELECT * FROM feedbacks WHERE page_id = '$id'";
    	$list = $this->dbconnect->connectQuery($sql);
		$result = array();
        if ($list){
            while($row = mysqli_fetch_assoc($list)){
                $result[] = $row;
            }
        }
        return $result;
    }
}
?>