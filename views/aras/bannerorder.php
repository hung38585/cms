<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/controllers/aracontroller.php";
include_once  dirname(__DIR__,2)."/models/post.php";
$temp = new templatecontroller();
if ($temp->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	//get id
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$link_arr = explode('/', $link);
	$id = $link_arr[count($link_arr)-1];
	$aracontroller = new aracontroller(); 
	$post = new post();
	$banners = $aracontroller->getListBanner($id); 
	if (isset($_POST['save'])) {
		$arr = array();
		for ($i=0; $i < count($_POST['stt']) ; $i++) { 
			$arr += [$_POST['stt'][$i] => $_POST['id'][$i]];
		}
		$related_id = '';
		ksort($arr);
		foreach ($arr as $key => $value) {
			$related_id .=",".$value;
		}
		$related_id = substr($related_id,1,strlen($related_id)-1);
		$colums = array(
			"list_banner_id" => $related_id
		);
		$post->update_post('aras',$id,$colums);
		header('Location: '.domain.'/ara');
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Banner Oder</title>
</head>
<body>
	<div class="container">
		<form method="POST" onsubmit="return validate();">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<b class="h4">Banner or</b> 
				<input type="submit" value="Save" name="save" class="btn btn-success" style="float: right;">
			</div>
			<div class="card-body row">
				<table class="table table-hover">
					<thead> 
						<th>Position</th>
						<th>Title</th> 
					</thead>
					<tbody>
					<form method="POST">
					<?php foreach ($banners as $key => $banner) {
					?>
					<tr>
						<td width="90">
							<input type="number" name="stt[]" value="<?php echo ++$key; ?>" class="form-control">
							<input type="hidden" name="id[]" value="<?php echo $banner['id']; ?>">
						</td>
						<td ><?php echo $banner['title']; ?></td>  
					</tr>	
					<?php
					} ?>
					</tbody>
				</table>
			</div>
		</div>
		</form>
	</div>
</body>
<script>
	function dem(arr,val) {
		d = 0;
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].value == val ) {
				d++;
			}
		}
		return d;
	}
	function validate() {
		var result = true;
		var stt = document.getElementsByName('stt[]');
		for (var i = 0; i <stt.length; i++) {
		    if (!stt[i].value ) {
		    	result = false;
		    	alert('Please enter position!');
		    }else{
		    	if (dem(stt,stt[i].value) > 1 ) {
		    		result = false;
		    		alert('Please change position!');
		    		break;
		    	}
		    }
		}
		return result;
	}
</script>
</html>