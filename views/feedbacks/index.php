<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/controllers/usercontroller.php";
include_once dirname(__DIR__,2)."/controllers/feedbackcontroller.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	$usercontroller = new usercontroller();
	$feedbackcontroller = new feedbackcontroller();
	if ($usercontroller->getLevelUser() != '1') {
		header('Location: '.domain.'/');
	} 
	$feedbacks = $feedbackcontroller->index();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Feedback</title>
</head>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<div class="row ">
					<div class="col-md-12">
						<b class="h4">Feedback</b>
					</div> 
				</div>
			</div>
			<div class="card-body row">
				<table class="table table-hover">
					<thead> 
						<th>Page id</th>
						<th>Question 1</th> 
						<th>Question 2</th> 
						<th>#</th> 
					</thead>
					<tbody> 
						<?php foreach ($feedbacks as $key => $feedback): ?>
							<tr>
								<td><?php echo $feedback['page_id']; ?></td> 
								<td><?php echo QUESTION1; ?></td>
								<td><?php echo QUESTION2; ?></td>
								<td><button class="btn btn-sm btn-primary viewfeedback" data-toggle="modal" data-target="#feedbackdetail" value="<?php echo $feedback['page_id']; ?>"><i class="fas fa-info-circle"></i></button></td>
							</tr> 	
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div> 
	</div>
	<!-- Modal detail-->  
	<div class="modal fade" id="feedbackdetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Feedback Detail</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body detailcontent">
					 
				</div>
			</div>
		</div>
	</div>
</body> 
<script type="text/javascript">
	$(".viewfeedback").click(function(){
		var page_id = $(this).val(); 
		$.ajax({
		    method: "POST",
		    url: "/views/feedbacks/qa.php",
		    data:{page_id: page_id} ,
		    success : function(response){
		    	$(".detailcontent").html(response);
		    }
		});
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
</html> 