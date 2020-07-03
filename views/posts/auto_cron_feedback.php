<?php   
include_once  dirname(__DIR__,2)."/config/config.php";
include_once  dirname(__DIR__,2)."/models/post.php";
include_once  dirname(__DIR__,2)."/models/feedback.php";
$post = new post();
$feedback = new feedback();
$local_file = dirname(__DIR__,2).'/models/feedback.csv';
$con = ftp_connect(ftp_server) or die("Could not connect to ftp_server");
$ftp = ftp_login($con, ftp_username, ftp_password);
$list_folder_csv = ftp_nlist($con,'csv');
foreach ($list_folder_csv as $key => $value) {
	ftp_get($con, $local_file, 'csv/'.$value.'/feedback.csv', FTP_BINARY); 
	$answer_of_question1 = array();
	$answer_of_question2 = array();
	$handle = fopen($local_file, 'r'); 
	while ($data= fgetcsv($handle, 1000, ",")){
		list($answer1,$answer2) = $data;
		echo $answer1.' '.$answer2.'<br>'; 
		array_push($answer_of_question1,$answer1);
		array_push($answer_of_question2,$answer2);
	} 
	$answer_of_question1 = array_count_values($answer_of_question1);
	$answer_of_question2 = array_count_values($answer_of_question2);
	foreach ($answer_of_question1 as $key_aoq1 => $aoq1) {
		$check = $feedback->checkfeedback($value,'1',$key_aoq1);
		if (!$check) {
			$colum = array(
				'page_id' => $value,
				'question' => '1',
				'answer' => $key_aoq1,
				'quantity' => $aoq1
			); 
			$post->insert('feedbacks',$colum); 
		}else{ 
			$page_feedback = $post->one_record('feedbacks',$check);  
			$quantity = $aoq1+$page_feedback[4];
			$colum = array( 
				'quantity' => $quantity
			); 
			$post->update_post('feedbacks',$check,$colum); 
		} 
	} 
	foreach ($answer_of_question2 as $key_aoq2 => $aoq2) {
		$colum = array(
			'page_id' => $value,
			'question' => '2',
			'answer' => $key_aoq2,
			'quantity' => $aoq2
		);
		$check = $feedback->checkfeedback($value,'2',$key_aoq2);
		if (!$check) {
			$post->insert('feedbacks',$colum); 
		}else{
			$page_feedback = $post->one_record('feedbacks',$check);  
			$quantity = $aoq2+$page_feedback[4];
			$colum = array( 
				'quantity' => $quantity
			); 
			$post->update_post('feedbacks',$check,$colum); 
		}  	
	} 
	fclose($handle);
	ftp_delete($con,'csv/'.$value.'/feedback.csv');
	ftp_rmdir($con,'csv/'.$value);
}
header('Location: '.domain.'/feedback');
?>