<?php 
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/views/shared/header.php"; 
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
$temp = new templatecontroller();
if ($temp->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	if (isset($_POST['saveflow'])) {  
		$temp->createFlow();
	}
	$users = $temp->listUser(); 
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create</title>
	<!-- select2 mutiple -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<b class="h4">Create Flow</b>
			</div>
			<form method="POST" onsubmit="return validate();">
			<div class="card-body"> 
				<p class="col-md-5 depterr text-danger" style="margin-left: 17.5%;"></p>
				<input type="hidden" name="deptcode" id="deptcode">
				<div class="form-group row">
					<label for="" class="ml-3 col-md-2">Name: </label>
					<input type="text" name="name" id="name" class="form-control col-md-5" >
					<p class="col-md-5 mt-2 text-danger nameerr" style="margin-left: 18%;"></p>
				</div>
				<div class="form-group row">
					<label class="col-md-2 ml-3">Flow:</label>
					<div class="col-md-2">
						<input type="checkbox" name="ar1" id="ar1">
						<label for="ar1">AR1</label>
					</div> 
					<div class="col-md-2">
						<input type="checkbox" name="ar2" id="ar2">
						<label for="ar2">AR2</label>
					</div>
					<div class="col-md-2">
						<input type="checkbox" name="ar3" id="ar3">
						<label for="ar3">AR3</label>
					</div>
				</div>
				<div class="form-group row">
					<label for="" class="ml-3 col-md-2">User Public: </label>
					<select name="user_public_id[]" id="userpublic" class="form-control col-md-5" multiple>
					<?php foreach ($users as $key => $user): ?>
						<option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
					<?php endforeach ?>
					</select>
				</div>
				<div class="form-group row">
					<label for="master" class="ml-3 col-md-2">Mater Public: </label>
					<input type="checkbox" id="master" name="master" class="mt-1">
				</div>
				<div class="form-group row">
					<a href="/flow" class="btn btn-danger mr-2" style="margin-left: 30px;">Back</a>
					<input type="submit" class="btn btn-success" value="Save" name="saveflow" >
				</div>
			</div>
			</form>
		</div>
	</div>
</body>
</html>
<script>
	$('#userpublic').select2();
	function validate() {
		var name = $('#name').val();
		if (!name) {
			$('.nameerr').html('Please enter Name.');
			return false;
		}else{
			if(name.length > 100){
				$('.nameerr').html('Max length of the name is 100.');
				return false;
			}
		}
	}
</script>