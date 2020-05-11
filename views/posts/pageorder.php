<?php  
	include_once dirname(__DIR__,2)."/config/config.php";
	include_once dirname(__DIR__,2)."/views/shared/header.php";
	include_once  dirname(__DIR__,2)."/models/post.php";
	include_once dirname(__DIR__,2)."/controllers/postcontroller.php";
	if (!isset($_SESSION['username'])){
	  header('Location: '.domain.'/login');
	}
	$post = new post();
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$link_arr = explode('/', $link);
	$id = $link_arr[count($link_arr)-1];
	if (isset($_SESSION['username'])) {
		if (isset($_POST['save'])) {
			$arr = array();
			for ($i=0; $i < count($_POST['stt']) ; $i++) { 
				$arr += [$_POST['stt'][$i] => $_POST['id'][$i]];
			}
			ksort($arr);
			$listautolink = $post->edit_autolink($id);
			foreach ($listautolink as $key => $value) {
				$post->delete_post('autolink',implode("",array_values($value)));
			}
			foreach ($arr as $key => $value) {
				$colums = [
					'page_id' => $id,
					'related_page_id' => $value
				];
				$post->insert('autolink',$colums);
			}
			$page = $post->one_record('posts',$id);
			if ($page[3] == '2') {
				$update_status = array(
					"status" => "3"
				);
				$post->update_post('posts',$id,$update_status);
			}
			header('Location: '.domain.'/');
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
				<div class="row">
					<h4 class="col-md-11">Page order</h4>
					<button type="submit" name="save" class="btn btn-outline-success ml-2">Save</button>
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