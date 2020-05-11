<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/controllers/usercontroller.php";
include_once dirname(__DIR__,2)."/controllers/organizationcontroller.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
$usercontroller = new usercontroller();
if ($usercontroller->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
if (!isset($_SESSION['username'])) {
	header('Location: '.domain.'/login');
}else{
 	$organcontroller = new organizationcontroller();
	$template = new templatecontroller();
	$temps = $template->get_list_template('');
 	$listorgan1 = $organcontroller->getListOrganc1();
 	if (isset($_POST['saveor'])) {
 		$organcontroller->create();
 	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Organization</title>
</head>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<b class="h4">Create Organization</b>
			</div>
			<form method="POST" onsubmit="return validate();">
			<div class="card-body">
				<div class="form-group row">
					<label for="" class="mr-3 ml-3 col-md-2">Department: </label>
					<select class="custom-select col-md-2" id="c1" name="c1">
			            <?php  
			            $option ='<option value="">---</option>';
			            foreach ($listorgan1 as $key => $value) {
			            	$option .= '<option value="'.substr($value['deptcode'],0,3).'">'.$value['name'].'</option>';
			            }
			            echo $option;
			            ?>
		            </select>
		            <select class="custom-select col-md-2 ml-2" id="c2" name="c2">
		              <option value="">---</option>
		            </select>
				</div>
				<div class="form-group row">
					<label for="" class="ml-3 col-md-2">Dept code <span class="text-danger">(number)</span>: </label>
					<div class="ml-3" id="codec1" style="width: 80px;">
						<input type="text" class="form-control" maxlength="3" id="deptc1">
					</div>
					<div class="ml-3" id="codec2" style="width: 80px;">
						<p class=" mt-1">000</p>
					</div>
					<div class="ml-3" id="codec3" style="width: 80px;">
						<p class=" mt-1">000</p>
					</div>
				</div>
				<p class="col-md-5 depterr text-danger" style="margin-left: 17.5%;"></p>
				<input type="hidden" name="deptcode" id="deptcode">
				<div class="form-group row">
					<label for="" class="ml-3 col-md-2">Name: </label>
					<input type="text" name="name" id="name" class="form-control col-md-3 ml-3" >
					<p class="col-md-5 mt-2 text-danger nameerr" style="margin-left: 18%;"></p>
				</div>
				<div class="form-group row" id="temp" style="display: none;">
					<label class="col-md-2 ml-3">Template: </label>
					<?php  
					foreach ($temps as $key => $value) {
					?>
					<input type="checkbox" class="mt-2" name="template_id[]" value="<?php echo $value['id'] ?>" id="<?php echo "key".$key; ?>">
					<label for="<?php echo "key".$key; ?>" class="mr-4"><?php echo $value['name']; ?></label>
					<?php
					}
					?>
					<p class="col-md-8 mt-2 text-danger temperr" style="margin-left: 18%;"></p>
				</div>
				<div class="form-group row" id="folder" style="display: none;">
					<label class="col-md-2 ml-3">Folder: </label>
					<input type="text" name="folder" class="form-control col-md-3 ml-3" id="foldername">
					<p class="col-md-8 mt-2 text-danger foldererr" style="margin-left: 18%;"></p>
				</div>
				<div class="form-group row">
					<a href="/organization" class="btn btn-danger mr-2" style="margin-left: 30px;">Back</a>
					<input type="submit" class="btn btn-success" value="Save" name="saveor" >
				</div>
			</div>
			</form>
		</div>
	</div>	
</body>
<script type="text/javascript">
	function validate() {
		var result = true, deptc1 = $("#deptc1").val(), deptc2 = '000', deptc3 = '000';
		var d = 0;
		$(".depterr").html("");
		$(".nameerr").html("");
		$(".temperr").html("");
		
		//set dept code
		if ($("#deptc3").length) {
		  	deptc3 = $("#deptc3").val();
		  	deptc2 = $("select#c2 option:selected").val();
		  	deptc1 = $("select#c1 option:selected").val();
		  	folder = $("#foldername").val();
		  	format = /[!@#$%^&*()+\-=\[\]{};':"\\|,.<>\?]/;
		  	$("[name='template_id[]']:checked").each(function(){
				d++;
			});
			if (d == 0) {
				result = false;
				$(".temperr").html("Please change Template!");
			}
			if (!folder) {
				result = false;
				$(".foldererr").html("Please enter Folder name!");
			}
			if (format.test(folder)) {
				$(".foldererr").html("Don't enter special characters!");
			}
		  	if (deptc3 == '000') {
				result = false;
				$(".depterr").html("Dept code other 000!");
			}
			validation(deptc3);
		}else{
			if ($("#deptc2").length) {
				deptc2 = $("#deptc2").val();
		  		deptc1 = $("select#c1 option:selected").val();
		  		if (deptc2 == '000') {
					result = false;
					$(".depterr").html("Dept code other 000!");
				}
				validation(deptc2);
			}else{
				if (deptc1 == '000') {
					result = false;
					$(".depterr").html("Dept code other 000!");
				}
				validation(deptc1);
			}
		}
		$("#deptcode").val(deptc1+deptc2+deptc3);
		var deptcode = $("#deptcode").val();
		//validate
		var name = $("#name").val();
		if (!name) {
			result = false;
			$(".nameerr").html("Please enter Name!");
		}
		$.ajax({
			async: false,
			method: "POST",
			url: "/views/organizations/checkname.php",
			data:{deptcode: deptcode} ,
			success : function(response){
				if (response == 1) {
					result = false;
					$(".depterr").html("Deptcode already exist!");
				}
			}
		});
		return result;
	}
	function validation(value) {
		if (!value) {
			result = false;
			$(".depterr").html("Please enter Dept code!");
		}else{
			if (value.length <3 ) {
				result = false;
				$(".depterr").html("Length requied is 3!");
			}else{
				if (!Number(value) ) {
					result = false;
					$(".depterr").html("Dept code requied is number!");
				}
			}
			if (value < 0) {
				result = false;
				$(".depterr").html("Dept code must be greater than 0!");
			}
		}
	}
	$("select#c1").change(function(){
		var deptcode = $(this).val();
		if (deptcode) {
			$.ajax({
	    		method: "POST",
			    url: "/views/organizations/getlistc2.php",
			    data:{deptcode: deptcode},
			    success : function(response){
			       	$("#c2").html(response);
			    }
	    	});
	    	$("#codec1").html('<p class=" mt-1">'+deptcode+'</p>');
	    	$("#codec2").html('<input type="text" class="form-control" id="deptc2" maxlength="3">' );
	    	$("#codec3").html('<p class=" mt-1">000</p>');
	    	$("#temp").css("display", "none");
			$("#folder").css("display", "none");	
		}else{
			$("#c2").html('<option value="">---</option>');
			$("#codec1").html('<input type="text" class="form-control" maxlength="3" id="deptc1">');
			$("#codec2").html('<p class=" mt-1">000</p>');
			$("#codec3").html('<p class=" mt-1">000</p>');
			$("#temp").css("display", "none");
			$("#folder").css("display", "none");	
		}
  	});
  	$("select#c2").change(function(){
		var deptcode = $(this).val();
		if (deptcode) {
			$("#codec2").html('<p class=" mt-1">'+deptcode+'</p>');
			$("#codec3").html('<input type="text" class="form-control" maxlength="3" id="deptc3">');
			$("#temp").css("display", "");
			$("#folder").css("display", "");	
		}else{
			$("#codec2").html('<input type="text" class="form-control" maxlength="3" id="deptc2">');
			$("#codec3").html('<p class=" mt-1">000</p>');
			$("#temp").css("display", "none");
			$("#folder").css("display", "none");	
		}
  	});
</script>
</html>