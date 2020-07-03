<?php 
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/views/shared/header.php"; 
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/controllers/aracontroller.php";
$temp = new templatecontroller();
if ($temp->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	$aracontroller = new aracontroller();
	if (isset($_POST['saveara'])) {
	 	$aracontroller->create();
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
				<b class="h4">Create Areas</b>
			</div>
			<form method="POST" onsubmit="return validate();">
			<div class="card-body"> 
				<div class="form-group row">
					<label for="" class="ml-3 col-md-2">Name: </label>
					<input type="text" name="name" id="name" class="form-control col-md-5" placeholder="Enter name">
					<p class="col-md-5 mt-2 text-danger nameerr" style="margin-left: 17%;"></p>
				</div>
				<div class="form-group row">
					<label class="col-md-2 ml-3">Style:</label>
					<div class="col-md-2 row">
						<input type="number" name="width" id="width" class="form-control" placeholder="Width(px)" min="30" max="1000"> 
						<p class="mt-2 text-danger widtherr" ></p>
					</div> 
					<div class="col-md-2 row" style="margin-left: 10px;">
						<input type="number" name="height" id="height" class="form-control" placeholder="Height(px)" min="10" max="500"> 
						<p class="mt-2 text-danger heighterr" ></p>
					</div> 
					<div class="col-md-2">
						<input type="radio" name="style" id="horizontal" checked value="1"> 
						<label for="horizontal">Horizontal: </label>
					</div> 
					<div class="col-md-2">
						<input type="radio" name="style" id="vertical" value="0"> 
						<label for="vertical">Vertical: </label>
					</div> 
				</div>
				<div class="form-group row">
					<label for="" class="ml-3 col-md-2">Quantity: </label>
					<input type="number" name="quantity" id="quantity" class="form-control col-md-5" placeholder="Enter quantity" min="1" max="10">
					<p class="col-md-5 mt-2 text-danger quantityerr" style="margin-left: 17%;"></p>
				</div>
				<div class="form-group row">
					<a href="/ara" class="btn btn-danger mr-2" style="margin-left: 30px;">Back</a>
					<input type="submit" class="btn btn-success" value="Save" name="saveara" >
				</div>
			</div>
			</form>
		</div>
	</div>
</body>
</html>
<script>
	function validate() {
		var name = $('#name').val();
		var width = $('#width').val();
		var height = $('#height').val();
		var quantity = $('#quantity').val();
		result = true;
		if (!name) {
			$('.nameerr').html('Please enter Name.');
			result = false;
		}else{
			if(name.length > 100){
				$('.nameerr').html('Max length of the name is 100.');
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
		if (!width) {
			$('.quantityerr').html('Please enter Quantity.');
			result = false;
		}
		return result;
	} 
</script>