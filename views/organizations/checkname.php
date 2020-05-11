<?php  
include_once dirname(__DIR__,2)."/controllers/organizationcontroller.php";
$organ = new organizationcontroller();
$deptcode = $_POST['deptcode'];
$organ = $organ->checkName($deptcode);
if ($organ) {
	echo "1";
}else{
	echo "2";
}
?>