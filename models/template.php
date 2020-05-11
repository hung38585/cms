<?php  
include_once  dirname(__DIR__)."\models\connectdb.php";
include_once  dirname(__DIR__)."\config\config.php";
include_once  dirname(__DIR__)."\models\post.php";
class template 
{
	private $dbconnect;
	private $post;
	function __construct()
	{
		$this->dbconnect = new connectdb();
		$this->post = new post();
		if ($this->dbconnect->checkDBConnection()) {
			return true;
		}else{
			return false;
		}	
	}
	public function list_template($id)
    {
        if ($id) {
            $sql = "SELECT * FROM template WHERE id in ($id)";
        }else{
            $sql = "SELECT * FROM template";
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
    public function insert_template($namefolder,$urlhtml,$urlcs,$namecs)
    {
    	$dir = dirname(__DIR__)."/templates/".$namefolder;
    	$list_folder = scandir(dirname(__DIR__)."/templates/");
		$result = true;
		foreach ($list_folder as $key => $value) {
			if ($value == $namefolder) {
				$result = false;
			}
		}
		if ($result) {
			mkdir($dir);	
		}
        if ($urlcs) {
           if ($zip->open($urlcs) === TRUE) {
                $zip->extractTo($dir);
                $zip->close();
            }
        }
		$zip = new ZipArchive;
    	move_uploaded_file($urlhtml, $dir."/".$namefolder.".html");
    }
    public function delete_template($id)
    {
    	$dir = dirname(__DIR__)."/";
    	$result = $this->post->one_record('template',$id);
    	unlink($dir.$result[2]);
    	$listfile = scandir($dir.$result[3]);
    	unset($listfile[0]);
    	unset($listfile[1]);
    	foreach ($listfile as $key => $value) {
    		$listcs = scandir($dir.$result[3]."/".$value);
    		foreach ($listcs as $key => $csvalue) {
    			if (!is_dir($csvalue)) {
    				unlink($dir.$result[3]."/".$value."/".$csvalue);
    			}
    		}
    		rmdir($dir.$result[3]."/".$value);
    	}
    	rmdir($dir.$result[3]);
    }
}
?>