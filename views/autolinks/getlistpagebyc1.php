<?php 
include_once dirname(__DIR__,2)."/models/autolink.php";
$autolink = new autolink();
$deptcode = $_POST['deptcode'];
$userid = $autolink->get_userid($deptcode);
$listpage = array();
foreach ($userid as $key => $value) {
	$listpage += $autolink->get_listpage_by_userid($value['id']);
}
$result = array();
foreach ($listpage as $key => $value) {
	$result[] = $value;
}
echo json_encode($result);
?>