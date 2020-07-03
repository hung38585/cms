<?php  
include_once  dirname(__DIR__,2)."/models/feedback.php";
include_once  dirname(__DIR__,2)."/config/config.php";
$feedback = new feedback();
$page_id = $_POST['page_id'];
$listfeedback = $feedback->getfeedback($page_id); 
$qa1 = '<p class="font-weight-bold">Question1:</p><p> <span>';
$qa2 = '<p class="font-weight-bold">Question2:</p><p> <span>';    
foreach ($listfeedback as $key => $value) {
	if ($value['question'] == 1 ) {
		switch ($value['answer']) {
			case '1':
				$answer = ANSWER_Q1_1;
				break;
			case '2':
				$answer = ANSWER_Q1_2;
				break;
			case '3':
				$answer = ANSWER_Q1_3;
				break;	
			default:
				# code...
				break;
		}
		$qa1 .= $answer.'('.$value['quantity'].') ';
	}
}    		
foreach ($listfeedback as $key => $value) {
	if ($value['question'] == 2 ) {
		switch ($value['answer']) {
			case '1':
				$answer = ANSWER_Q2_1;
				break;
			case '2':
				$answer = ANSWER_Q2_2;
				break;
			case '3':
				$answer = ANSWER_Q2_3;
				break;	
			default:
				# code...
				break;
		}
		$qa2 .= $answer.'('.$value['quantity'].') ';
	}
}
$qa1 .= '</span></p>';
$qa2 .= '</span></p>';    
echo $qa1.$qa2;
?>