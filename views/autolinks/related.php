<?php  	
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
include_once dirname(__DIR__,2)."/models/organization.php";
include_once  dirname(__DIR__,2)."/models/post.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
$temp = new templatecontroller();
if ($temp->getLevelUser() != '1') {
	header('Location: '.domain.'/');
}
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_arr = explode('/', $request);
$id = $request_arr[count($request_arr)-1];
if (!isset($_SESSION['username'])){
	header('Location: '.domain.'/login');
}else{
	$organ = new organization();
	$post = new post();
	$organc1 = $organ->get_list_organc1();
	if ($id != 'create') {
		$autolink_record = $post->one_record('autolink',$id); 
	}
}
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_arr = explode('/', $request);
$id = $request_arr[count($request_arr)-1];
?>
<title>Related page</title>
<body>
	<div class="container">
		<div class="card mr-4 mt-3">
			<div class="card-header">
				<b class="h4">Autolink</b>
			</div>
			<div class="card-body row">
				<form class="col-md-12" method="POST" action="/autolink/pageorder" onsubmit="return validate();">
					<input type="hidden" name="autolinkid" value="<?php echo $id; ?>">
					<div class="form-group col-md-12 row">
						<label for="" class="col-md-1">Name: </label>
						<input type="text" name="name" class="form-control col-md-5" id="name" value="<?php if($id != 'create'){
							echo $autolink_record[1];
						} ?>">
						<span class="text-danger nameerr col-md-12" style="margin-left: 70px;"></span>
					</div>
					<div class="form-group col-md-12 row">
						<div class="col-md-6" style="height: 90px;">
							<input type="radio" name="kinddept" value="1" id="kindtemplate" <?php if ($id == "create") {
								echo "checked";
							}elseif ($autolink_record[3] == 1) {
								echo "checked";
							} ?>>
							<input type="hidden" name="status" id="status" value="1">
							<label for="kindtemplate" class="col-md-8">Kind Template</label>
							<div class="col-md-12 row">
								<select name="kind" class="custom-select col-md-5" id="kind" style="display: <?php if ($id != "create" && $autolink_record[3] == 2) {echo "none";} ?>;" >
									<option value="">Select kind</option>
									<option value="1" <?php if ($id != "create") {
										if ($autolink_record[3] == 1) { echo "selected";}
									} ?>><?php echo CATEGORY_1; ?></option>
									<option value="2" <?php if ($id != "create") {
										if ($autolink_record[3] == 2) { echo "selected";}
									} ?>><?php echo CATEGORY_2; ?></option>
									<option value="3" <?php if ($id != "create") {
										if ($autolink_record[3] == 3) { echo "selected";}
									} ?>><?php echo CATEGORY_3; ?></option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<input type="radio" name="kinddept" value="2" id="department" <?php if ($id != "create" && $autolink_record[3] == 2) {
								echo "checked";
							} ?>>
							<label for="department" class="col-md-8">Department</label>
							<div class="col-md-12 form-group row" id="organ" style="display: <?php if ($id != "create" && $autolink_record[3] == 2) {
								echo "";
							}else{ echo "none"; } ?>;">
								<select class="custom-select mr-2" id="c1" style="width: 135px;">
									<option value="">---</option>
									<?php  
									foreach ($organc1 as $key => $value) {
										$selected = '';
										if ($id != "create" && $autolink_record[3] == '2' && substr($value['deptcode'],0,3) == substr($autolink_record[4],0,3)) {
											$selected = "selected";
										}else{
											$selected = "";
										}
										echo '<option value="'.substr($value['deptcode'],0,3).'" '.$selected.'>'.$value['name'].'</option>';
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
					</div>
					<div class="col-md-12">
							<h4>Related page <input type="submit" class="btn btn-success ml-2" value="Next"></h4>
						<table class="table table-hover table-borderless">
							<thead>
								<tr>
									<th width="30">#</th>
									<th width="">Title</th>
								</tr>
							</thead>
							<tbody id="listpage">
								
							</tbody>
						</table>					
					</div>
				</form>	
			</div>
		</div>
	</div>	 			
</body>
<script type="text/javascript">
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
			$("#c3").html('<option value="">---</option>');
	    	//get list by c1
	    	$.ajax({
	    		method: "POST",
	    		url: "/views/autolinks/getlistpagebyc1.php",
	    		data:{deptcode: deptcode},
	    		dataType: 'JSON',
	    		success : function(response){
	    			var list = "";
	    			for (var i = 0; i < response.length; i++) {
	    				list +='<tr><td><input type="checkbox" name="stt[]" value="'+response[i].id+'"></td><td>'+response[i].title+'</td></tr>'
	    			}
	    			$("#listpage").html(list);
	    		}
	    	});
	    	$("#deptcode").val(deptcode+"000000");
	    }else{
	    	$("#c2").html('<option value="">---</option>');
	    	$("#c3").html('<option value="">---</option>');
	    	$("#listpage").html("");
	    }
	});
	$("select#c2").change(function(){
		var deptcode = $(this).val();
		if (deptcode) {
			deptcode2 = $("select#c1 option:selected").val() + deptcode;
			$.ajax({
				method: "POST",
				url: "/views/organizations/getlistc3.php",
				data:{deptcode: deptcode2},
				success : function(response){
					$("#c3").html(response);
				}
			});
			$("#deptcode").val(deptcode2+"000");
		}else{
			$("#c3").html('<option value="">---</option>');
		}
		//get list by c2
		deptcode2 = $("select#c1 option:selected").val() + $(this).val();
		$.ajax({
			method: "POST",
			url: "/views/autolinks/getlistpagebyc1.php",
			data:{deptcode: deptcode2},
			dataType: 'JSON',
			success : function(response){
				var list = "";
				for (var i = 0; i < response.length; i++) {
					list +='<tr><td><input type="checkbox" name="stt[]" value="'+response[i].id+'"></td><td>'+response[i].title+'</td></tr>'
				}
				$("#listpage").html(list);
			}
		});
	});	
	$("select#c3").change(function(){
		var deptcode = $(this).val();
		deptcode = $("select#c1 option:selected").val() + $("select#c2 option:selected").val() + deptcode;
		$.ajax({
			method: "POST",
			url: "/views/autolinks/getlistpagebyc1.php",
			data:{deptcode: deptcode},
			dataType: 'JSON',
			success : function(response){
				var list = "";
				for (var i = 0; i < response.length; i++) {
					list +='<tr><td><input type="checkbox" name="stt[]" value="'+response[i].id+'"></td><td>'+response[i].title+'</td></tr>'
				}
				$("#listpage").html(list);
			}
		});
		$("#deptcode").val(deptcode);
		if (deptcode.length < 9) {
			$("#deptcode").val(deptcode+"000");
		}
	});	
	$("select#kind").change(function(){
		var kind = $(this).val();
		if (kind) {
			$.ajax({
				method: "POST",
				url: "/views/autolinks/getlistpagebykind.php",
				data:{kind: kind},
				dataType: 'JSON',
				success : function(response){
					var list = "";
					for (var i = 0; i < response.length; i++) {
						list +='<tr><td><input type="checkbox" name="stt[]" value="'+response[i].id+'"></td><td>'+response[i].title+'</td></tr>'
					}
					$("#listpage").html(list);
				}
			});
		}else{
			$("#listpage").html("");
		}
	});
	$("#department").click(function(){
		$("#kind").css("display", "none");
		$("#organ").css("display", "");
		$("#listpage").html("");
		$("#status").val("2");
	});
	$("#kindtemplate").click(function(){
		$("#organ").css("display", "none");
		$("#kind").css("display", "");
		$("#listpage").html("");
		$("select#c1").val("");
		$("#c2").html('<option value="">---</option>');
	    $("#c3").html('<option value="">---</option>');
		$("#status").val("1");
	});
	function validate() {
		var name = $("#name").val();
		result = true;
		if (!name) {
			result = false;
			$(".nameerr").html("Please enter Name!");
		}
		var ischeck = false;
	    $("input:checkbox:checked").each(function () {
	      if ($(this).val()) {
	        ischeck = true;
	      }
	    });
	    if (!ischeck) {
	      	alert('Please choose page!');
	      	result = false;
	    }
		return result;
	}
	<?php  
	if ($id != "create" && $autolink_record[3] == "2") {
	?>
		var dcode = <?php echo $autolink_record[4]; ?>;
		dcodec2 = dcode.toString().substr(0,3);
		dcodec3 = dcode.toString().substr(0,6);
		deptcodec2 = dcode.toString().substr(3,3);
		deptcodec3 = dcode.toString().substr(6,3);
		
		$.ajax({
			async: false,
			method: "POST",
			url: "/views/organizations/getlistc2.php",
			data:{deptcode: dcodec2},
			success : function(response){
				$("#c2").html(response);
				$("select#c2").val(deptcodec2);
			}
		});
		if (deptcodec2 == "000") {
			$("select#c2").val("");
		}else{
			$.ajax({
				async: false,
				method: "POST",
				url: "/views/organizations/getlistc3.php",
				data:{deptcode: dcodec3},
				success : function(response){
					$("#c3").html(response);
					if (deptcodec3 != "000") {
						$("select#c3").val(deptcodec3);
					}
				}
			});
		}
		deptcode = dcodec2;
		if (deptcodec2 != '000') {
			deptcode += deptcodec2;
		}
		if (deptcodec3 != '000') {
			deptcode += deptcodec3;
		}
		var id = <?php echo $id; ?>;
		var listid = getlistpage(id);
		$.ajax({
			method: "POST",
			url: "/views/autolinks/getlistpagebyc1.php",
			data:{deptcode: deptcode},
			dataType: 'JSON',
			success : function(response){
				var list = "";
				console.log(response);
				for (var i = 0; i < response.length; i++) {
					if (listid.indexOf(response[i].id) != '-1') {
						list +='<tr><td><input type="checkbox" name="stt[]" value="'+response[i].id+'" checked></td><td>'+response[i].title+'</td></tr>';
					}else{
						list +='<tr><td><input type="checkbox" name="stt[]" value="'+response[i].id+'"></td><td>'+response[i].title+'</td></tr>';
					}
				}
				$("#listpage").html(list);
			}
		});
	<?php	
	}
	if ($id != 'create' && $autolink_record[3] == "1") {
	?>
		var kind = $("select#kind option:selected").val();
		var id = <?php echo $id; ?>;
		var listid = getlistpage(id);
		$.ajax({
			method: "POST",
			url: "/views/autolinks/getlistpagebykind.php",
			data:{kind: kind},
			dataType: 'JSON',
			success : function(response){
				var list = "";
				for (var i = 0; i < response.length; i++) {
					if (listid.indexOf(response[i].id) != '-1') {
						list +='<tr><td><input type="checkbox" name="stt[]" value="'+response[i].id+'" checked></td><td>'+response[i].title+'</td></tr>';
					}else{
						list +='<tr><td><input type="checkbox" name="stt[]" value="'+response[i].id+'"></td><td>'+response[i].title+'</td></tr>';
					}	
				}
				$("#listpage").html(list);
			}
		});
	<?php
	}
	?>
	function getlistpage(id) {
		$.ajax({
			async: false,
			method: "POST",
			url: "/views/autolinks/getlistpageid.php",
			data:{id: id},
			dataType: 'JSON',
			success : function(response){
				var list = new Array();
				listid=response;
			}
		});
		return listid;
	}	
</script>