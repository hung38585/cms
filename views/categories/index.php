<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/controllers/categorycontroller.php";
$temp = new templatecontroller();
if ($temp->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	$category = new categorycontroller();
	$listcategories = $category->index();
	if (isset($_POST['savecategory'])) {
		$listcategories = $category->create();
	} 
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Category</title>
</head>
<body>
	<div class="container">
		<form method="POST">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<b class="h4">Category</b>
				<button class="btn btn-outline-danger mb-2 ml-1"  type="button"  id="del" style="float: right;"><i class="far fa-trash-alt"></i></button>
				<button class="btn btn-outline-info" name="savecategory" id="savecategory" style="float: right;">Add <i class="fas fa-plus-square"></i></button> 
				<input type="text" class="form-control mr-2" name="name" id="name" placeholder="Category name" style="display: inline; width: 30%; float: right;">
			</div>
			<div class="card-body row">
				<table class="table table-hover">
					<thead>
						<th><input type="checkbox" class="" id="checkAll"></th>
						<th>Id</th>
						<th>Name</th>
						<th>#</th>
					</thead>
					<tbody>
					<?php foreach ($listcategories as $key => $value): ?>
						<tr>
							<td><input type="checkbox" name="id[]" value="<?php echo $value['id'] ?>"></td>
							<td><?php echo $value['id']; ?></td>
							<td><?php echo $value['name']; ?></td>
							<td></td>
						</tr>
					<?php endforeach ?>	
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
</html>
<script>
	$("#savecategory").click(function(){
		var name = $("#name").val();
		if (!name) {
			alert('Please enter Name category!');
			return false;
		}
	});
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

</script>