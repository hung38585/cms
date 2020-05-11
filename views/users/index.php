<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/controllers/usercontroller.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	$usercontroller = new usercontroller();
	if ($usercontroller->getLevelUser() != '1') {
		header('Location: '.domain.'/');
	}
	$users = $usercontroller->index();
	if (isset($_POST['deleteall'])) {
		$usercontroller->delete();
	}
	if (isset($_POST['import'])) {
		$usercontroller->importCSV();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>User</title>
</head>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<div class="row ">
					<div class="col-md-2">
						<b class="h4">User</b>
					</div>
					<div class="col-md-6">
						<form method="POST" enctype="multipart/form-data">
							<label>File csv: </label>
							<input type="file" name="csv" id="changecsv" accept=".csv">
							<input type="submit" value="Import" class="btn btn-primary" name="import" id="import">
							<input type="submit" value="Export" class="btn btn-success" name="export">
						</form>
					</div>
					<div class="col-md-4">
						<button class="btn btn-outline-danger mb-2 ml-1"  type="button"  id="del" style="float: right;"><i class="far fa-trash-alt"></i></button>
						<a href="user/create" class="btn btn-outline-info " style="float: right;">Add <i class="fas fa-plus-square"></i></a>
					</div>
				</div>
			</div>
			<div class="card-body row">
				<table class="table table-hover">
					<thead>
						<th><input type="checkbox" class="" id="checkAll"></th>
						<th>ID</th>
						<th>Username</th>
						<th>Level</th>
						<th>#</th>
					</thead>
					<tbody>
					<form method="POST">
					<?php  
					foreach ($users as $key => $value) {
						if ($value['level'] != '1' ) {
						?>	
						<tr>
							<td><input type="checkbox" name="id[]" value="<?php echo $value['id'] ?>"></td>
							<td><?php echo $value['id']; ?></td>
							<td><?php echo $value['username']; ?></td>
							<td><?php echo $value['level']; ?></td>
							<td></td>
						</tr>
						<?php
						}
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Model Delete -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Delele</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
						<button  name="deleteall" class="btn btn-outline-danger">Delete</button>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>	
</body>
<script type="text/javascript">
	$("#checkAll").click(function () {
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
	$("#del").click(function () {
		var ischeck = false;
		$("input:checkbox:checked").each(function () {
			if ($(this).val()) {
				ischeck = true;
			}
		});
		if (!ischeck) {
			alert('Please choose!');
		}else{
			$('#exampleModal').modal('show');
		}
	});
	$("#import").click(function(){
		var file = $("#changecsv").val();
		var result = true;
	  	var ext = $("#changecsv").val().split(".").pop().toLowerCase();
		if (!file) {
			alert('Please upload file CSV!');
			result = false;
		}else{
			if($.inArray(ext, ["csv"]) == -1) {
				alert('Please upload file CSV!');
				result = false;
			}
		}
		return result;  
	});
</script>
</html>
<?php  
if (isset($_SESSION['username'])){
	if (isset($_POST['export'])) {
	    $usercontroller->exportCSV();
  	}
}
?>