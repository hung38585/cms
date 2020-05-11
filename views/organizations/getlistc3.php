<?php  
include_once dirname(__DIR__,2)."/controllers/organizationcontroller.php";
$organcontroller = new organizationcontroller();
$deptcode = $_POST['deptcode'];
$listorgan = $organcontroller->getListOrganc3($deptcode);
$option = '<option value="">---</option>';
foreach ($listorgan as $key => $value) {
	$option .= '<option value="'.substr($value['deptcode'],6,3).'">'.$value['name'].'</option>';
}
echo $option;
?>