<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
include_once  dirname(__DIR__,2)."/models/post.php";
include_once dirname(__DIR__,2)."/controllers/postcontroller.php";
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	$post = new post();
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
		$colum = array(
			"name" => htmlspecialchars(addslashes($_POST['name'])),
			"related_page_id" => $related_id,
			"status" => $_POST['status'],
			"relate" => $_POST['relate'] 
		);
		if ($_POST['autolink_id'] == "create") {
			$post->insert('autolink',$colum);
		}else{
			$post->update_post('autolink',$_POST['autolink_id'],$colum);
		}	
		header('Location: '.domain.'/autolink');
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Page order</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<div class="container">
		<div class="card mt-5">
			<div class="card-header">
				<form method="POST" onsubmit="return validate();">
					<input type="hidden" name="name" value="<?php echo $_POST['name'] ?>">
					<input type="hidden" name="status" value="<?php echo $_POST['status'] ?>">
					<input type="hidden" name="autolink_id" value="<?php echo $_POST['autolinkid'] ?>">
					<input type="hidden" name="relate" value="<?php if($_POST['status'] == 1){
					echo $_POST['kind'];	
					}else{echo $_POST['deptcode'];}  ?>">
				<div class="row">
					<h4 class="col-md-11">Page order</h4>
					<button type="submit" name="save" class="btn btn-success ml-2">Save</button>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-borderless">
					<thead>
						<tr>
							<th width="30">Position</th>
							<th width="">Title</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($_POST['stt'] as $key => $value) {
							$record = $post->one_record('posts',$value);
						?>
						<tr>
							<td><input type="number" name="stt[]" min="1" class="form-control stt" value="<?php echo ++$key; ?>" style="width: 60px;">
								<input type="hidden" name="id[]" value="<?php echo $record[0]; ?>">
							</td>
							<td><p class="mt-1"><?php echo $record[1]; ?></p></td>	
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
				</form>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
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