<?php 
include_once dirname(__DIR__,2)."/controllers/postcontroller.php";
include_once  dirname(__DIR__,2)."/models/post.php";
include_once  dirname(__DIR__,2)."/models/path.php";
$content = new postcontroller();
$template = new post();
$path = new path();
$id = $_POST['id'];
$template_id = $template->one_record('posts',$id);
$template_url = $content->get_urltemplate($template_id[5],2);
$urlcs = $content->get_urltemplate($template_id[5],3);
$file = file_get_contents(dirname(__DIR__,2)."/".$template_url,'r+');
$content = $content->get_content('posts',$id);
$listfolder = scandir(dirname(__DIR__,2)."/".$urlcs);
if ($urlcs) {
	if(!$path->checkfolder($listfolder,'css')){
	    $listcss = scandir(dirname(__DIR__,2)."/".$urlcs."/css");
	    unset($listcss[0]);
	    unset($listcss[1]);
	    foreach($listcss as $value){
	        echo '<link rel="stylesheet" type="text/css" href="../../'.$urlcs.'/css/'.$value.'">';
	    }
	}	
}
$newcontent = str_replace('<p class="formcreatepost"></p>',$content,$file);
//Auto link
$link = $template->get_list_page_public($id);
$link_value = '';
foreach ($link as $key => $value) {
  	$page = $template->one_record('posts',implode("",$value));
  	if ($id != $page[0]) {
  		$link_value .= '<li><a href="'.$path->getlinkpub($page[4]).'">'.$page[1].'</a></li>';
  	}
}
$filetxt = file_get_contents(dirname(__DIR__,2)."/models/link.txt",'r+');
$filetxt = str_replace('autolink',$link_value,$filetxt);
$newcontent = str_replace('<!-- auto link -->',$filetxt,$newcontent);

$file_feedback = file_get_contents(dirname(__DIR__,2)."/models/feedback.html",'r+');
//Replace Q&A
  		$file_feedback = str_replace('QUESTION1',QUESTION1,$file_feedback);
  		$file_feedback = str_replace('QUESTION2',QUESTION2,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q1_1',ANSWER_Q1_1,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q1_2',ANSWER_Q1_2,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q1_3',ANSWER_Q1_3,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q2_1',ANSWER_Q2_1,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q2_2',ANSWER_Q2_2,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q2_3',ANSWER_Q2_3,$file_feedback); 
$newcontent = str_replace('<!-- feedback -->',$file_feedback,$newcontent); 
echo $newcontent;
?>