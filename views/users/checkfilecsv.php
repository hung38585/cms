<?php  
//$filename = $_POST['file'];
//$filename = substr($filename, 12,strlen($filename)-12);
$filename = $_FILES["file"]["name"];
$handle = fopen($filename, 'r');
while ($data= fgetcsv($handle, 100, ","))
{
	list($id,$username, $password, $level, $deptcode) = $data;
	echo $id.' '.$username . ' ' . $password . ' ' . $level.' '.$deptcode.'<br>';
}
fclose($handle);
echo $filename;
?>