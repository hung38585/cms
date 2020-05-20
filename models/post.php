<?php 
include_once  dirname(__DIR__)."\models\connectdb.php";
include_once  dirname(__DIR__)."\config\config.php";
include_once  dirname(__DIR__)."\models\path.php";
class post extends connectdb
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
	//Index
	public function list($table,$where = array(),$limit,$search,$title,$condition_id,$condition_status)
	{
		//PAGE LIST
        $lm = $limit;
        //tim tong so record
        $sql_get_total_records ="SELECT count(id) as total from $table";
        $get_total_records = $this->dbconnect->connectQuery($sql_get_total_records);
        $row = mysqli_fetch_assoc($get_total_records);
        $total_records = $row['total'];
        //Trang hien tai
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1; //trnag 1
        if ($current_page > ceil($total_records / $lm)) {
        	$current_page = ceil($total_records / $lm);        	    
        }
        //So record tren 1 trang
        $total_page = ceil($total_records / $lm);
        // Giới hạn current_page trong khoảng 1 đến total_page
        if ($current_page > $total_page){
            $current_page = $total_page;
        }
        else if ($current_page < 1){
            $current_page = 1;
        }
        //Tim vị trí record bắt đầu
        $start = ($current_page - 1) * $lm;
		$condition='';
        if ($where) {
            foreach ($where as $key => $value) { 
                if ($key != 'template.id' && $key != 'flows.id') {
                    $value = "'".$value."'";
                }
                $condition .= $key . " = ".$value. " and ";
            }
        }
        $condition = substr($condition, 0, -5);  
        $sqlselect =  "SELECT $table.* FROM $table ";    
        if (array_key_exists("flows.ar1", $where) || array_key_exists("flows.ar2", $where) || array_key_exists("flows.ar3", $where)) {
            $sqlselect =  "SELECT $table.* FROM $table,template,flows ";
        }
        $id = '';
        if ($condition_id) {
            foreach ($condition_id as $key => $value) {
                $id .= $value.",";
            }
            $id = substr($id,0,strlen($id)-1);
            $id = ' AND user_id in ('.$id.')';
        }
        $cond_status = '';
        if ($condition_status) {
            foreach ($condition_status as $key => $value) {
                $cond_status .= $value.",";
            }
            $cond_status = substr($cond_status,0,strlen($cond_status)-1);
            $cond_status = ' AND status in ('.$cond_status.')'; 
        }
        if ($condition) {
            if ($search) {
                $sql = $sqlselect." WHERE ".$condition." and ".$title." LIKE '%".$search."%' ".$id.$cond_status." ORDER BY created_at DESC LIMIT ".$start.",".$lm;
            }else{
                $sql = $sqlselect." WHERE ".$condition.$id.$cond_status." ORDER BY created_at DESC LIMIT ".$start.",".$lm;   
            }
        }else{
            if ($search) {
                $sql = $sqlselect." WHERE ".$title." LIKE '%".$search."%' ".$id.$cond_status." ORDER BY created_at DESC LIMIT ".$start.",".$lm;
            }else{
                $id = substr($id,4,strlen($id)-1);
                if ($id) {
                    $id = " WHERE ".$id;
                }else{
                    if ($cond_status) {
                        $cond_status = " WHERE ".$id;
                    }
                }
                $sql = $sqlselect.$id.$cond_status." ORDER BY created_at DESC LIMIT ".$start.",".$lm;   
            }
        }
        $list = $this->dbconnect->connectQuery($sql);
        $result = array();
        if ($list){
            while($row = mysqli_fetch_assoc($list)){
                $result[] = $row;
            }
        }
        return $result;
	}
	//Create
	public function insert($table,$columns = array())
	{
        $conn = mysqli_connect(host_name, user_name, db_password, db_name);
        $path = new path();
		$sql = 'INSERT INTO '.$table;
		$sql .= '('.implode(',',array_keys($columns)).') VALUES';
		$sql .= "('".implode("','", array_values($columns))."')";
        if ($result = mysqli_query($conn, $sql)) {
           $last_id = mysqli_insert_id($conn);
        }
        if ($table == 'posts') {
            $sql = "SELECT * From $table where id = $last_id";
            $record = $this->dbconnect->connectQuery($sql);
            if ($record){
                $row = mysqli_fetch_row($record);
            }
            if ($row[4]) {
                $folder = $path->getfolder($row[4]);
                $key_filename = $path->getname($row[4]);
                $filename = $folder[$key_filename];
                unset($folder[$key_filename]);
                $filecontent = $path->setcontent($row[1],$row[2]);
                $namefolder = dirname(__DIR__)."/upload/";
                $url = '';
                foreach ($folder as $key => $value) {
                    $url .= $value.'/';
                }
                $filename = $namefolder.$url.$filename.".html";
                $file = fopen($filename, 'w');
                fwrite($file, $filecontent);
            }else{
                $filename = dirname(__DIR__)."/upload/".$row[0].".html";
                $file = fopen($filename, 'w');
                $filecontent = $path->setcontent($row[1],$row[2]);
                fwrite($file, $filecontent);
            }   
        }
		return $result;
	}
	//Update
	public function update_post($table,$id,$columns = array())
	{
		date_default_timezone_set('Asia/Ho_Chi_Minh');
        $timeup = date("Y-m-d H:i:s");
        $colum = '';
        foreach ($columns as $key => $value) {
        	$colum .= $key . " = '".$value."', ";
        }
        $colum = substr($colum, 0, -2);
        $sql = 'UPDATE '.$table.' SET '.$colum." WHERE id = '".$id."'";
		$result = $this->dbconnect->connectQuery($sql);
		return $result;
	}
	//Lấy 1 record
	public function one_record($table,$id)
	{
		$sql = "SELECT * From $table where id = $id";
        $result = $this->dbconnect->connectQuery($sql);
        if ($result){
            $row = mysqli_fetch_row($result);
        }
        return $row;
	}
    //get last row in table
    public function getlastid($table)
    {
        $sql = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
        $result = $this->dbconnect->connectQuery($sql);
        $row = mysqli_fetch_row($result);
        if ($row) {
            return $row[0];
        }else{
            return 0;
        }
    }
	//Delete
	public function delete_post($table,$id)
	{
		$sql = "DELETE From $table where id = $id";
       	$result = $this->dbconnect->connectQuery($sql);
        return $result;
	}
    public function delete_all($table)
    {
        $sql = "DELETE FROM $table";
        $result = $this->dbconnect->connectQuery($sql);
        return $result;
    }
	//Cập nhật Status = 'Public' khi upload 
	public function setstatusupload($table,$id)
	{
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $timepub = date("Y-m-d H:i:s");
		$sql = "UPDATE $table
		SET status = '2',published_at = '$timepub'
		where id = $id";
		$result = $this->dbconnect->connectQuery($sql);
		return $result;
	}
    //get version page
    public function get_version($id)
    {
        $sql = "SELECT * FROM versions WHERE post_id = '$id'";
        $list = $this->dbconnect->connectQuery($sql);
        $result = array();
        if ($list){
            while($row = mysqli_fetch_assoc($list)){
                $result[] = $row;
            }
        }
        return $result;
    }
	public function total_records($table,$limit,$search,$status,$where = array(),$condition_id,$condition_status)
	{
        $condition='';
        if ($where) {
            foreach ($where as $key => $value) {
                if ($key != 'template.id' && $key != 'flows.id') {
                    $value = "'".$value."'";
                }
                $condition .= $key . " = ".$value. " and ";
            }
        }
        $id = '';
        if ($condition_id) {
            foreach ($condition_id as $key => $value) {
                $id .= $value.",";
            }
            $id = substr($id,0,strlen($id)-1);
            $id = ' user_id in ('.$id.')';
        }
        $cond_status = '';
        if ($condition_status) {
            foreach ($condition_status as $key => $value) {
                $cond_status .= $value.",";
            }
            $cond_status = substr($cond_status,0,strlen($cond_status)-1);
            $cond_status = ' AND status in ('.$cond_status.') AND'; 
        }
        $addtable = '';
        if (array_key_exists("flows.ar1", $where) || array_key_exists("flows.ar2", $where) || array_key_exists("flows.ar3", $where)) {
            $addtable = ",template,flows";
        }
        if ($status) {
            $sql ="SELECT count($table.id) as total from $table".$addtable." WHERE ".$condition.$id.$cond_status." title LIKE '%".$search."%' and status = '".$status."'";
        }else{
            $sql ="SELECT count($table.id) as total from $table".$addtable." WHERE ".$condition.$id.$cond_status." title LIKE '%".$search."%'";
        } 
        $get_total_records = $this->dbconnect->connectQuery($sql);
        $row = mysqli_fetch_assoc($get_total_records);
        $total_records = $row['total'];
        return $total_records = ceil($total_records/$limit);
	}
	public function gettitle($table,$colum,$keyword,$id)
	{
		$sql = "SELECT $colum from ".$table;
		$result = $this->dbconnect->connectQuery($sql);
		$list = array();
        if ($result){
            while($row = mysqli_fetch_assoc($result)){
                $list[] = $row;
            }
        } 
        $kq = true;
        foreach ($list as $key => $value) {
            if ($value[$colum] == $keyword) {
                if ($id) {
                    $sql="SELECT title from $table where id='$id'";
                    $result = $this->dbconnect->connectQuery($sql);
                    $row = mysqli_fetch_assoc($result);
                    $title = $row['title'];
                    if ($value[$colum] == $title) {
                        $kq = true;
                    }else{
                        $kq = false;
                    }
                }else{
                    $kq = false;
                } 
            }
        }    
        return $kq;
	}
    public function getcontent($table,$id)
    {
        $sql = "SELECT content from ".$table." WHERE id = '".$id."'";
        $result = $this->dbconnect->connectQuery($sql);
        while ($row = mysqli_fetch_array($result)) {
            $content = $row['content'];
        }
        return $content;
    }
    public function getlistpage($category_id)
    {
        $sql = "SELECT * FROM posts WHERE template_id IN ( SELECT id FROM template WHERE category_id ='".$category_id."') AND STATUS != '1' ORDER BY created_at DESC ";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
    }
    public function get_list_page_public($id)
    {
        $sql = "SELECT related_page_id FROM autolink WHERE id='".$id."'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
    }
    public function checkpage($page_id,$related_page_id)
    {
        $sql = "SELECT id FROM autolink WHERE page_id ='".$page_id."' AND related_page_id ='".$related_page_id."'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
    }
    public function edit_autolink($id)
    {
        $sql = "SELECT id FROM autolink WHERE page_id ='".$id."'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
    }
    public function get_list_relatedid_by($id)
    {
        $sql = "SELECT page_id FROM autolink WHERE related_page_id='$id'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
    }
    public function get_autolink_id($id)
    {
        $sql = "SELECT id FROM autolink WHERE page_id='$id'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
    }
    public function get_listid_by_related($id)
    {
        $sql = "SELECT id FROM autolink WHERE related_page_id='$id'";
        $result = $this->dbconnect->connectQuery($sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] =$row;
        }
        return $list;
    }
    public function get_leveluser($username)
    {
        $sql = "SELECT level FROM user WHERE username='$username'";
        $result = $this->dbconnect->connectQuery($sql);
        while ($row = mysqli_fetch_array($result)) {
            $level = $row['level'];
        }
        return $level;
    }
    public function get_iduser($username)
    {
        $sql = "SELECT id FROM user WHERE username='$username'";
        $result = $this->dbconnect->connectQuery($sql);
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
        }
        return $id;
    }
    public function get_deptcode($username)
    {
        $sql = "SELECT deptcode FROM user WHERE username='$username'";
        $result = $this->dbconnect->connectQuery($sql);
        while ($row = mysqli_fetch_array($result)) {
            $deptcode = $row['deptcode'];
        }
        return $deptcode;
    }
    public function get_tc($deptcode)
    {
        $c2 = substr($deptcode,3,3);
        $c3 = substr($deptcode,6,3);
        $result = 1;
        if ($c2 != '000') {
            if ($c3 != '000') {
                $result = '3';
            }else{
                $result = '2';
            }
        }
        return $result;
    }
    public function get_list_userid_by_deptcode($deptcode)
    {
        $deptcode = substr($deptcode,0,6);
        if (substr($deptcode,3,3) == '000') {
            $deptcode = substr($deptcode,0,3);
        }
        $sql = "SELECT id FROM user WHERE deptcode LIKE '".$deptcode."%'";
        $id = array();
        $result = $this->dbconnect->connectQuery($sql);
        while ($row = mysqli_fetch_array($result)) {
            $id[] = $row['id'];
        }
        return $id;
    }
    public function get_ar_value($ar,$id)
    {
        $sql = "SELECT flows.".$ar." FROM posts,flows,template WHERE posts.template_id = template.id and template.flow_id = flows.id AND posts.id = ".$id;
        $result = $this->dbconnect->connectQuery($sql);
        $row = mysqli_fetch_array($result);
        return $row[$ar];
    }
}
?>