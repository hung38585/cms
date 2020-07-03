<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
include_once dirname(__DIR__,2)."/controllers/autolinkcontroller.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
$temp = new templatecontroller();
if ($temp->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	$autolinkcontroller = new autolinkcontroller();
	$autolink = $autolinkcontroller->index();
	if (isset($_POST['deleteall'])) {
		$autolinkcontroller->delete();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>AutoLink</title>
</head>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<b class="h4">AutoLink</b>
				<button class="btn btn-outline-danger mb-2 ml-1"  type="button"  id="del" style="float: right;"><i class="far fa-trash-alt"></i></button>
				<a href="autolink/create" class="btn btn-outline-info " style="float: right;">Add <i class="fas fa-plus-square"></i></a>
			</div>
			<form method="POST">
			<div class="card-body row">
				<table class="table table-hover">
					<thead>
						<th><input type="checkbox" class="" id="checkAll"></th>
						<th>Name</th>
						<th>Code</th>
						<th>#</th>
					</thead>
					<tbody>
					<?php  
					foreach ($autolink as $key => $value) {
					?>
						<tr>
							<td><input type="checkbox" name="id[]" value="<?php echo $value['id'] ?>"></td>
							<td><?php echo $value['name']; ?></td>
							<td><?php echo htmlentities('<!-- auto link id='.$value['id'].'-->'); ?></td>
							<td><a href="autolink/edit/<?php echo $value['id'] ?>" class="btn btn-outline-success"><i class="far fa-edit"></i></a></td>
						</tr>
					<?php	
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
</script>
</html>