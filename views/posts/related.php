<?php  
	include_once dirname(__DIR__,2)."/config/config.php";
	include_once dirname(__DIR__,2)."/views/shared/header.php";
	include_once  dirname(__DIR__,2)."/models/post.php";
	if (!isset($_SESSION['username'])){
	  header('Location: '.domain.'/login');
	}
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$link_arr = explode('/', $link);
	$id = $link_arr[count($link_arr)-1];
	$post = new post();
	$page = $post->one_record('posts',$id);
	$category = $post->one_record('template',$page[5]);
	$list = $post->getlistpage($category[4]);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Related page</title>
</head>
<body>
	<div class="container">
		<div class="card mt-5">
			<div class="card-header">
				<form method="POST" onsubmit="return validate();" action="/post/pageorder/<?php echo $id; ?>" >
				<div class="row">
					<h4 class="col-md-10">Related page</h4>
					<a class="btn btn-outline-danger" href="/" style="width: 60px;"><i class="fas fa-long-arrow-alt-left" style="font-size: 19px;"></i></a>
					<button type="submit" name="next" class="btn btn-outline-success ml-2">Next</button>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-borderless">
					<thead>
						<tr>
							<th width="30">#</th>
							<th width="">Title</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!$list) {
						?>
						<td></td>
						<td><p class="font-weight-bold" >Related page is empty!</p></td>
						<?php	
						}  
						foreach ($list as $key => $value) {
							if ($value['id'] != $id) {
							?>
							<tr>
								<td>
									<input type="checkbox" name="stt[]" value="<?php echo $value['id'] ?>" <?php if ($post->checkpage($id,$value['id'])) {
										echo "checked";
									} ?>>
								</td>
								<td><?php echo $value['title']; ?></td>
							</tr>
							<?php	
							}
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
	function validate() {
	    var ischeck = false;
	    $("input:checkbox:checked").each(function () {
	      if ($(this).val()) {
	        ischeck = true;
	      }
	    });
	    if (!ischeck) {
	      alert('Please choose!');
	      return false;
	    }else{
	    	return true;
	    }
	}
</script>
</html>