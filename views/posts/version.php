<!DOCTYPE html>
<html>
<head>
	<title>Version page</title>
	<?php  
	include_once dirname(__DIR__,2)."/config/config.php";
	include_once dirname(__DIR__,2)."/views/shared/header.php";
	include_once  dirname(__DIR__,2)."/models/post.php";
	if (!isset($_SESSION['username'])){
	  header('Location: '.domain.'/login');
	}
	if (isrollback != "on") {
		header('Location: '.domain.'/');	
	}
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$link_arr = explode('/', $link);
	$id = $link_arr[count($link_arr)-1];
	$version = new post();
	$versions = $version->get_version($id);
	if (isset($_SESSION['username'])) {
	  	if (isset($_POST['rollback'])) {
		  	$id_ver = $_POST['rollback'];
		  	$ver_record = $version->one_record('versions',$id_ver);
		  	$lastid =  $version->getlastid('postrollback');
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < 5; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    $randomString =substr($lastid.$randomString,0,6);
		  	$colums = array(
		  		"title" => $ver_record[1],
		  		"content" => $ver_record[2],
		  		"template_id" => $ver_record[3],
		  	);
		  	$current_record = $version->one_record('posts',$id);
		  	if ($current_record[3] == "2") {
		  		$colums = array(
		  		"title" => $ver_record[1],
		  		"content" => $ver_record[2],
		  		"template_id" => $ver_record[3],
		  		"status" => "3"
		  	);
		  	}
		  	$colums_ver = array(
					"title" => $current_record[1],
					"content" => $current_record[2],
					"template_id" => $current_record[5],
					"post_id" => $id,
					"version" => $randomString
			);
			if ($current_record[8]) {
				$colums_ver = array(
					"title" => $current_record[1],
					"content" => $current_record[2],
					"template_id" => $current_record[5],
					"post_id" => $id,
					"published_at" => $current_record[8],
					"version" => $randomString
				);
			}		
		  	$version->insert('versions',$colums_ver);
		  	$version->update_post('posts',$id,$colums);
		  	header('Location: '.domain.'/');
	  	}
	}  
	?>
</head>
<body>
	<div class="container">
		<div class="card mt-5">
			<div class="card-header">
				<div class="row">
					<h4 class="col-md-11">Version page</h4>
					<a class="btn btn-outline-success" href="/" style="width: 60px;"><i class="fas fa-long-arrow-alt-left" style="font-size: 19px;"></i></a>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-borderless">
					<thead>
						<tr>
							<th width="400">Title</th>
							<th width="300">Version</th>
							<th width="300">Published at</th>
							<th width="300">#</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!$versions) {
						?>
						<td></td>
						<td><p class="font-weight-bold" style="margin-left: 120px;">Version is empty!</p></td>
						<td></td>
						<td></td>
						<?php	
						}  
						foreach ($versions as $key => $value) {
							?>
							<tr>
								<td><?php echo $value['title']; ?></td>
								<td><?php echo $value['version']; ?></td>
								<td><?php echo $value['published_at']; ?></td>
								<td class="row">
									<button type="button" class="btn btn-outline-info preview mr-2" data-toggle="modal" data-target="#preview" value="<?php echo $value['id'] ?>"><i class="fas fa-eye"></i></button> 
									<form method="POST" onsubmit="return isrollback();">
										<button type="submit" name="rollback" class="btn btn-outline-success rollback" value="<?php echo $value['id'] ?>"><i class="fas fa-undo-alt"></i></button>
									</form> 
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>	
	<!-- Modal previrew -->
	<div class="modal" id="preview" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Preview</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="content" style="height: 500px; overflow-y: auto;"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(".preview").click(function(){
		var id = $(this).val();
		$.ajax({
			method: "POST",
			url: "/views/posts/getcontentversion.php",
			data:{id: id } ,
			success : function(response){
				//console.log(response);
	        	$(".content").html(response);
	        }
	    });
	});
	function isrollback() {
		if (confirm("Rollback this page")) {
			return true;	
		}else{
			return false;
		}		
	}
</script>
</html>