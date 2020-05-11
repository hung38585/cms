<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/controllers/usercontroller.php";
include_once dirname(__DIR__,2)."/models/organization.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
$usercontroller = new usercontroller();
if (!isset($_SESSION['username'])) {
	header('Location: '.domain.'/login');
}else{
	$organ = new organization();
	$organc1 = $organ->get_list_organc1();
	if (isset($_POST['saveuser'])) {
    	$usercontroller->create();
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
       			<b class="h4">Create User</b>
    		</div>
    		<form method="POST" onsubmit="return validate();">
			    <div class="card-body row">
			    	<div class="card-body row">
			    	<div class="form-group col-md-7 row">
			    		<div class="form-group row col-md-12">
			    			<label class="col-md-3">Username</label>
				    		<input type="text" name="username" class="form-control col-md-9" id="username">
				    		<span id="usernameerr" class="text-danger ml-3"></span>
				    	</div>
			    		<div class="col-md-12 form-group row">
			    			<label class="col-md-3">Level</label>
			    			<select name="level" class="custom-select col-md-3" id="level">
				    			<option value="3">Publicer</option>
			    				<option value="2">Creater</option>
			    				<option value="4">AR</option>
			    				<option value="1">Master</option>
				    		</select>
			    		</div>
			    		<div class="col-md-12 form-group row" id="department" style="display: none;">
			    			<label class="col-md-3">Department</label>
			    			<select class="custom-select mr-2" id="c1" style="width: 135px;">
			    				<option value="">---</option>
			    				<?php  
			    				foreach ($organc1 as $key => $value) {
			    					echo '<option value="'.substr($value['deptcode'],0,3).'">'.$value['name'].'</option>';
			    				}
			    				?>
				    		</select>
				    		<select class="custom-select mr-2" id="c2" style="width: 135px;">
			    				<option value="">---</option>
				    		</select>
				    		<select class="custom-select" id="c3" style="width: 134.5px;">
			    				<option value="">---</option>
				    		</select>
				    		<span class="depterr text-danger ml-3"></span>
				    		<input type="hidden" name="deptcode" id="deptcode">
			    		</div>	
			    	</div>
			    	<div class="col-md-5 row">
			    		<div class="form-group row col-md-12">
			    			<label class="col-md-5 mt-1">Password:<span class="text-danger"> </span></label>
			    			<input type="password" name="password" class="form-control col-md-7" id="password">
			    			<span id="passworderr" class="text-danger ml-3"></span>
			    		</div>
			    		<div class="form-group row col-md-12">
			    			<label class="col-md-5">Confirmpassword</label>
			    			<input type="password" name="confirmpassword" id="confirmpassword" class="form-control col-md-7">
			    			<span id="confirmpassworderr" class="text-danger ml-3"></span>
			    		</div>
			    	</div>
			    	<div class="row col-md-12">
			    		<a href="/user" class="btn btn-danger mr-1" style="margin-left: 82.5%; height: 38px;">Back</a>
			    		<input type="submit" name="saveuser" value="Save" class=" btn btn-success" style="height: 38px;">
			    	</div>
			    </div>
			    </div>
			</form>    
		</div>
	</div>				
</body>
<script type="text/javascript">
	function validate() {
		var username = $("#username").val();
		var password = $("#password").val();
		var confirmpassword = $("#confirmpassword").val();
		var level = $("select#level option:selected").val();
		$("#usernameerr").html("");
		$("#passworderr").html("");
		$(".depterr").html("");
		$("#confirmpassworderr").html("");
		result = true;
		if (!username) {
			$("#usernameerr").html("Please enter username!");
			result = false;
		}else{ 
		    $.ajax({
		    	async: false,
		      	method: "POST",
		      	url: "/views/users/checkusername.php",
		      	data:{username: username } ,
		      	success : function(response){
		        	if (response == 2) {
		        		result = false;
		        		$("#usernameerr").html("Username already exist!");
		       	 	}
		      	}
		    });
		}
		if (!password) {
			$("#passworderr").html("Please enter password!");
			result = false;
		}
		if (!confirmpassword) {
			$("#confirmpassworderr").html("Please enter confirmpassword!");
			result = false;
		}else{
			if (password && password != confirmpassword) {
				$("#confirmpassworderr").html("Confirmpassword incorrect!");
				result = false;
			}
		}
		var c1 = $("select#c1 option:selected").val();
		var c2 = $("select#c2 option:selected").val();
		var c3 = $("select#c3 option:selected").val();
		if (level == 2) {
			if (!c1 || !c2 || !c3) {
				$(".depterr").html("Please change Department!");
				result = false;
			}else{
				$("#deptcode").val(c1+c2+c3);
			}
		}
		if (level == 4) {
			if (!c1) {
				$(".depterr").html("Please change Department!");
				result = false;
			}else{
				if (!c2) {
					c2 = '000';
				}
				if (!c3) {
					c3 = '000';
				}
				$("#deptcode").val(c1+c2+c3);
			}
		}
		return result;
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
		}else{
			$("#c2").html('<option value="">---</option>');
			$("#c3").html('<option value="">---</option>');
		}
  	});
  	$("select#c2").change(function(){
		var deptcode = $(this).val();
		deptcode = $("select#c1 option:selected").val() + deptcode;
		if (deptcode) {
			$.ajax({
	    		method: "POST",
			    url: "/views/organizations/getlistc3.php",
			    data:{deptcode: deptcode},
			    success : function(response){
			       	$("#c3").html(response);
			    }
	    	});
	    	$("#c3").html('<option value="">---</option>');
		}else{
			$("#c3").html('<option value="">---</option>');
		}
  	});
  	$("select#level").change(function(){
		var level = $(this).val();
		if (level != 2 && level != 4) {
			$("#department").css("display", "none");	
		}else{
			$("#department").css("display", "");
		}
  	});
</script>
</html>