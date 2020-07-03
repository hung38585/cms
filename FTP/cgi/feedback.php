<?php 
$dir = dirname(__DIR__).'/csv/';
$page_id = $_POST['page_id']; 
$answer1 = $_POST['question1'];
$answer2 = $_POST['question2'];
$filecsv = scandir($dir);
if (in_array($page_id, $filecsv)){
	$myfile = fopen($dir.$page_id."/feedback.csv","a");
	fputcsv($myfile,array($answer1,$answer2)); 
	fclose($myfile); 
}else{
	mkdir($dir.$page_id);
	$myfile = fopen($dir.$page_id."/feedback.csv","w");
	fputcsv($myfile,array($answer1,$answer2));
	fclose($myfile);
}
header('Location: ' . $_SERVER['HTTP_REFERER']);  	 
?>