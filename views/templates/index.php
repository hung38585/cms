<?php  	
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}
$temp = new templatecontroller();
if ($temp->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
$list = $temp->index();
if (isset($_SESSION['username'])) {
	if (isset($_POST['deleteall'])) {
		$temp->delete();
	}
}
?>
<title>Template</title>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
			<div class="card-header">
					<b class="h4"> Templates</b>
					<button class="btn btn-outline-danger mb-2 ml-1"  type="button"  id="del" style="float: right;"><i class="far fa-trash-alt"></i></button>
					<a href="template/create" class="btn btn-outline-info " style="float: right;">Add <i class="fas fa-plus-square"></i></a>
			</div>
			<form method="post">
				<div class="card-body row">
					<div class="col-md-12 row">
						
					</div>
					<table class="table table-hover">
						<thead>
							<th><input type="checkbox" class="" id="checkAll"></th>
							<th>ID</th>
							<th>Name</th>
							<th>URL HTML</th>
							<th>#</th>
						</thead>
						<tbody>
							<?php  
							foreach ($list as $key => $template) {
								?>
								<tr>
									<td><input type="checkbox" name="id[]" value="<?php echo $template['id']; ?>"> </td>
									<td><?php echo $template['id']; ?></td>
									<td><?php echo $template['name']; ?></td>
									<td><?php echo $template['urltemplate']; ?></td>
									<td>
										<a href="/template/detail/<?php echo $template['id']; ?>" target="_blank">
											<i class="fas fa-info-circle text-success"></i>
										</a>
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