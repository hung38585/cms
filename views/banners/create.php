<?php 
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/views/shared/header.php"; 
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/controllers/aracontroller.php";
include_once dirname(__DIR__,2)."/controllers/bannercontroller.php";
$temp = new templatecontroller();
if ($temp->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	$aracontroller = new aracontroller();
	$bannercontroller = new bannercontroller();
	$aras = $aracontroller->index();
	if (isset($_POST['savebanner'])) {
	 	$bannercontroller->create();
	} 
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create</title>
</head>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<b class="h4">Create Banner</b>
			</div>
			<form method="POST" enctype="multipart/form-data" onsubmit="return validate();">
			<div class="card-body"> 
				<div class="form-group row">
					<label for="" class="ml-3 col-md-1">Title: </label>
					<input type="text" name="title" id="title" class="form-control col-md-6" placeholder="Enter title">
					<p class="col-md-5 mt-2 text-danger titleerr" style="margin-left: 8.3%;"></p>
				</div> 
				<div class="form-group row">
					<label class="col-md-1 ml-3">Style:</label>
					<div class="col-md-2 row">
						<input type="number" name="width" id="width" class="form-control" placeholder="Enter width(%)" min="30" max="100"> 
						<p class="mt-2 text-danger widtherr" ></p>
					</div> 
					<div class="col-md-2 row" style="margin-left: 10px;">
						<input type="number" name="height" id="height" class="form-control" placeholder="Enter height(%)" min="10" max="100"> 
						<p class="mt-2 text-danger heighterr" ></p>
					</div>  
				</div>
				<div class="form-group row">
					<label for="" class="ml-3 col-md-1">Ara: </label>
					<div class="col-md-3 row"> 
						<select name="ara_id" id="ara" class="form-control">
							<option value="">Select Area</option>
							<?php foreach ($aras as $key => $ara): ?>
								<option value="<?php echo $ara['id']; ?>"><?php echo $ara['name']; ?></option>
							<?php endforeach ?>
						</select>
						<p class="mt-2 text-danger araerr" ></p>
					</div>
					<label for="" class="ml-3 col-md-1">Image: </label>
					<div class="col-md-5">
						<input type="file" name="image" id="image">
						<p class="col-md-5 mt-2 text-danger imageerr" ></p>
					</div> 
				</div>
				<div class="form-group row">
					<a href="/ara" class="btn btn-danger mr-2" style="margin-left: 30px;">Back</a>
					<input type="submit" class="btn btn-success" value="Save" name="savebanner" >
				</div>
			</div>
			</form>
		</div>
	</div>
</body>
</html>
<script>
	function validate() {
		var title = $('#title').val();
		var width = $('#width').val();
		var height = $('#height').val();
		var ara = $('#ara').val();
		var image = $('#image').val();
		result = true;
		if (!title) {
			$('.titleerr').html('Please enter Title.');
			result = false;
		}else{
			if(title.length > 255){
				$('.titleerr').html('Max length of the title is 255.');
				result = false;
			}
		}
		if (!width) {
			$('.widtherr').html('Please enter Width.');
			result = false;
		}
		if (!height) {
			$('.heighterr').html('Please enter Height.');
			result = false;
		}
		if (!ara) {
			$('.araerr').html('Please select Ara.');
			result = false;
		}
		if (!image) {
			$('.imageerr').html('Please select Image.');
			result = false;
		}
		return result;
	} 
</script>