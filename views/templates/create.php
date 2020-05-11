<?php  
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
if (!isset($_SESSION['username'])){
  header('Location: '.domain.'/views/posts/login.php');
}
if (isset($_SESSION['username'])) {
	if (isset($_POST['savetemplate'])) {
		$temp = new templatecontroller();
		$temp->create();
	}
}
?>
<title>Create Template</title>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
    		<div class="card-header">
       			<b class="h4">Create</b>
    		</div>
    		<form method="POST" enctype="multipart/form-data" onsubmit = "return validateForm();" >
			    <div class="card-body row">
			    	<div class="form-group col-md-6 row">
			    		<div class="form-group row col-md-12">
			    			<label class="col-md-3">Name:<span class="text-danger"> *</span></label>
				    		<input type="text" name="name" class="form-control col-md-8" id="name">
				    		<span id="nameerr" class="text-danger ml-3"></span>
				    	</div>
			    		<div class="col-md-12 form-group row">
			    			<label class="col-md-3">Category</label>
			    			<select name="category" class="custom-select col-md-3">
				    			<option value="1"><?php echo CATEGORY_1; ?></option>
				    			<option value="2"><?php echo CATEGORY_2; ?></option>
				    			<option value="3"><?php echo CATEGORY_3; ?></option>
				    		</select>
			    		</div>
			    	</div>
			    	<div class="col-md-6 row">
			    		<div class="form-group row col-md-12">
			    			<label class="col-md-4 mt-1">Html:<span class="text-danger"> *</span></label>
			    			<input type="file" name="htmlupload" class="form-control col-md-8" id="htmlupload">
			    			<span id="urlerr" class="text-danger ml-3"></span>
			    			<input type="hidden" name="urlhtml" id="urlhtml">
			    		</div>
			    		<div class="form-group row col-md-12">
			    			<label class="col-md-4">Css/script(.zip):</label>
			    			<input type="file" name="csupload" id="csupload" class="form-control col-md-8">
			    			<span id="urlcserr" class="text-danger ml-3"></span>
			    			<input type="hidden" name="urlcs" id="urlcs">
			    		</div>
			    	</div>
			    	<div class="row col-md-12">
			    		<a href="/template" class="btn btn-outline-success mr-1" style="margin-left: 82.5%; height: 38px;">Back</a>
			    		<input type="submit" name="savetemplate" value="Save" class=" btn btn-outline-info" style="height: 38px;">
			    	</div>
			    </div>
			</form>    
		</div>
	</div>				
</body>
<script type="text/javascript">
	function validateForm()  {
	    var name = document.getElementById("name").value;
	    var urlhtml = document.getElementById("urlhtml").value;
	    var urlcs = document.getElementById("urlcs").value;
	    var result = true;
	    if (name == '') {
	    	$("#nameerr").text("Enter name's template!");
	    	result = false;
	    }else{
	    	if (name == 0) {
		        $("#nameerr").text("Don't only enter space!");
		        result = false;
		    }else{
		    	if (name.length > 20) {
			        $("#nameerr").text("Maximum name is 20!");
			        result = false;
			    }else{
			    	$.ajax({
				    	async: false,
						method: "POST",
						url: "/views/templates/checkname.php",
						data:{name: name} ,
						success : function(response){
					        if (response == 1) {
					        	result = false;
					        	$("#nameerr").text("Title already exist!");
					       	}else{
					       		$("#nameerr").text("");	
					       	}
						}
					});
			    }
		    }
	    }
	    if (urlhtml == '') {
	    	$("#urlerr").text("Change url html");
	    	result = false;
	    }else{
	    	if (urlhtml.indexOf(".html") == -1) {
	    		$("#urlerr").text("File is not html!");
	    		result = false;
	    	}else{
	    		$("#urlerr").text("");
	    	}
	    }
	    if (urlcs) {
	    	if (urlcs.indexOf(".zip") == -1) {
		    	$("#urlcserr").text("File is not file zip");
		    	result = false;
		    }else{
		    	var filecs = document.getElementById('csupload').files[0].size;
		    	if (filecs > 4110000) {
		    		$("#urlcserr").text("Max size is 40Mb!");
		    	}else{
		    		$("#urlcserr").text("");	
		    	}
		    }
	    }
	    return result;
	}
	$("#htmlupload").change(function(){
		var name = $(this).val();
		name = name.replace(/^.*[\\\/]/, '');
		$("#urlhtml").val(name);
	});
	$("#csupload").change(function(){
		var name = $(this).val();
		name = name.replace(/^.*[\\\/]/, '');
		$("#urlcs").val(name);
	});
</script>