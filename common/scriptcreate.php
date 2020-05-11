<script type="text/javascript">
	$(document).ready(function(){
		var format = /[!@#$%^&*()+\-=\[\]{};':"\\|,.<>\/?]/;
		$("#create").click(function(){
			getfoldername = $("#namefolder").val().trim();
			if (!getfoldername || getfoldername == 0) {
				alert('Folder name is empty!');
			}else{
				if (format.test(getfoldername)) {
					alert("Don't enter special characters!");
				}else{
					if (getfoldername.length > 20) {
						alert('Folder name is less than 20 characters! ')
					}else{
						dir = $("#urlfolder").val();
						$.ajax({
							method: "POST",
							url: "/views/posts/checkfolder.php",
							data:{
								folder: getfoldername,
								dir: dir
							} ,
							success : function(response){
								if (!response) {
									alert('Folder name already exist');       
								}else{
									if (!$("#urlfolder").val()) {
										foldername = "'"+getfoldername.trim()+"'";
										$("#folder1").after('<button onclick="selectfolder('+foldername+',this.id)" id="firstfolder" class="btn" style="height: 20px; padding: 0;"><h6  style="margin: 0;"><i class="fas fa-folder"></i> '+getfoldername+'</h6></button/><br>');
										$("#namefolder").val('');
									}else{
										foldername = "'"+getfoldername+"'";
										$("#first").after('<button onclick="selectfolder('+foldername+')" class="btn col-md-3" value="'+getfoldername+'" style=" padding: 0"><h6><i class="fas fa-folder" style="font-size:32px"></i> <br>'+getfoldername+'</h6></button>');
										$("#namefolder").val('');
										$(".empty").html('');
									}
								}
							}
						});
					}
				}
			}
		});
		$("#setpath").click(function(){
			if (!$("#filename").val() || $("#filename").val() == 0) {
				alert('Please enter name file!');
				return false;
			}else{
				var filename = $("#filename").val().trim(); 
				if (filename.length > 30) {
					alert('Max is 30!')
				}else{
					var format2 = /[! @#$%^&*()+\-=\[\]{};':"\\|,.<>\/?]/;
					if (format2.test(filename)) {
						alert("Don't enter special characters!");
					}else{
						$.ajax({
							method: "POST",
							url: "/views/posts/checkfilename.php",
							data:{filename: filename } ,
							success : function(response){
								if (response == 2) {
									alert('Filename already exist');       
								}else{
									$("#path").val($("#urlfolder").val()+$("#filename").val());
									$('#changefolder').modal('hide');
									$("#showpath").html($("#path").val()+".html");
									$("#clear").show();
								}
							}
						});
					}
				}
			}
		});
	});
	$("#clear").click(function(){
		$("#path").val('');
		$("#showpath").html('');
		$("#clear").hide();
	});
	$("#home").click(function(){
		$("#urlfolder").val('');
		$("#content").html("");
	});
	function selectfolder(value,id) {
		if (id == 'firstfolder') {
			$("#urlfolder").val('');
		}
		var path = $("#urlfolder").val();
		$("#urlfolder").val($("#urlfolder").val()+value+'/');
		value = path+value; 
		$.ajax({
			method: "POST",
			url: "/views/posts/showfolder.php",
			data:{folder: value } ,
			success : function(response){
	        // var n = $.parseJSON(response);
	        //console.log(response);
	        $("#content").html(response);
		    }
		});
	}
	function back() {
		var url = $("#urlfolder").val();
		var str = '';
		arr = url.split("/");
		for (var i = 0; i < arr.length-3; i++) {
			arr[i] += "/";
			str += arr[i];
		}
		$("#urlfolder").val(str);
	    //alert(arr[arr.length-3]);
	    if (arr.length >= 3) {
	    	last = arr[arr.length-3];
	    }else{
	    	last = '';
	    }
	    if (last) {
	    	selectfolder(last,'');
	    }else{
	    	$("#content").html('');
	    }
	}
$(".close").click(function(){
	$('#changefolder').modal('hide');
});
if ($("#showpath").text() == '') {
	$("#clear").hide();
}
function validateForm()  {
	var title = document.getElementById("title").value;
	var path = document.getElementById("path").value
	var result = true;
	if (path == '') {
		$("#patherr").text("Please change URL Folder!");
		result = false;
	}else{
		$("#patherr").text("");
	}
	if (title == '') {
		$("#titleerr").text('Please enter your Title!');
		result = false;
	}else{
		if (title == 0) {
			$("#titleerr").text("Don't only enter space!");
			result = false;
		}else{
			if (title.length > 100) {
				$("#titleerr").text("Maximum title is 100!");
				result = false;
			}else{
				$("#titleerr").text("");
			}
		}
		 

	}
	$.ajax({
		async: false,
		method: "POST",
		url: "/views/posts/checktitle.php",
		data:{title: title} ,
		success : function(response){
			//console.log(response);
			if (response == 2) {
				result = false;
				$("#titleerr").text("Title already exist!");
			}
		}
	});
	return result;
}
</script>