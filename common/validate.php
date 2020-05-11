<script type = "text/javascript">
  function validateForm()  {
    var title = document.getElementById("title").value;
    var content = CKEDITOR.instances['editor1'].getData();
    var idpost = $("#idpost").val();
    var result = true;
    if (title == '') {
      $("#titleerr").text('Please enter your Title');
      result = false;
    }else{
    	$("#titleerr").text("");
      	if (title == 0) {
	        $("#titleerr").text("Don't only enter space!");
	        result = false;
      	}else{
      		$("#titleerr").text("");
      	}
      	if (title.length > 100) {
        	$("#titleerr").text("Maximum title is 100!");
        	result = false;
      	}
    }
    if (content == '') {
      $("#contenterr").text('Please enter your Content');
      result = false;
    }else{
    	$("#contenterr").text('');
    }
    $.ajax({
      async: false,
      method: "POST",
      url: "/views/posts/checktitle.php",
      data:{title: title, idpost: idpost} ,
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
