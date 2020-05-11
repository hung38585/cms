<?php 
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/common/validate.php";
include_once dirname(__DIR__,2)."/controllers/postcontroller.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/controllers/organizationcontroller.php";
include_once  dirname(__DIR__,2)."/models/post.php";
include_once  dirname(__DIR__,2)."/models/path.php";
if (!isset($_SESSION['username']) && !$_SESSION['username']){
  header('Location: '.domain.'/views/posts/login.php');
}
$postcontroller = new postcontroller();
$organ = new organizationcontroller();
$level = $postcontroller->getLevelUser();
if ($level != '1' && $level != '2') {
  header('Location: '.domain.'/');
}
$listtempid = '';
if ($level == '2') {
  $deptcode = $postcontroller->getDeptcode();
  $listtempid = $organ->getTemplateId($deptcode);
  $listtempid = $listtempid[0]['template_id'];
}
$templatecontroller = new templatecontroller();
$postmodel = new post();
$path = new path();
//get id
$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$link_arr = explode('/', $link);
$id = $link_arr[count($link_arr)-1];
$templates = $templatecontroller->get_list_template($listtempid);
//$id = $_GET['id'];
$record = $postmodel->one_record('posts',$id);
$temp = $postcontroller->get_urltemplate($record[5],2);
$urlcs = $postcontroller->get_urltemplate($record[5],3);
$listfolder = scandir(dirname(__DIR__,2)."/".$urlcs);
if ($urlcs) {
  if(!$path->checkfolder($listfolder,'css')){
    $listcss = scandir(dirname(__DIR__,2)."/".$urlcs."/css");
    unset($listcss[0]);
    unset($listcss[1]);
    foreach($listcss as $value){
      echo '<link rel="stylesheet" type="text/css" href="../../'.$urlcs.'/css/'.$value.'">';
    }
  }
  if(!$path->checkfolder($listfolder,'js')){
    $listjs = scandir(dirname(__DIR__,2)."/".$urlcs."/js");
    unset($listjs[0]);
    unset($listjs[1]);
    foreach($listjs as $value){
      echo '<script src="../../'.$urlcs.'/js/'.$value.'"></script>';
    }
  } 
}
$post = $postcontroller->edit($id);
if (isset($_SESSION['username'])){
  if (isset($_POST['updatepost'])) {
    $post = $postcontroller->update();
  }
  $template = $postcontroller->get_urltemplate($post[5],2);
}
?>
<title>Edit</title> 
<style type="text/css">
  #iframe {
    -ms-zoom: 0.5;
    -moz-transform: scale(0.5);
    -moz-transform-origin: 0 0;
    -o-transform: scale(0.5);
    -o-transform-origin: 0 0;
    -webkit-transform: scale(0.5);
    -webkit-transform-origin: 0 0;
  }
  .btnback a:hover {
    color: white;
  }  
</style>
<?php
$edit = '<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal" style="position: fixed; top: 33.5%; right: 1%; width: 95px;">CheckLink</button>
<a href="/" class="btn btn-danger" style="position: fixed; top: 46.5%; right: 1%; width: 95px;"><i class="mr-1 fas fa-arrow-left"></i>Back</a>
<div class="col-md-12" >
<button type="button" class="btn btn-success preview" data-toggle="modal" data-target=".bd-example-modal-xl" style="position: fixed; top: 53%; right: 1%; width:95px;" >Preview</button> 
<form method="POST" onsubmit = "return validateForm();">
<button name="updatepost" class="btn btn-primary" id="save"style="position: fixed; top: 40%; right: 1%;width: 95px;">Update</button>
<div class="form-group ">
<label >Title</label>
<input type="text" class="form-control" id="title" name="txttitle" value="'.$post[1].'" placeholder="" >
<span id="titleerr" class="text-danger"></span> 
</div>
<div class="form-group ">
<label >Content</label>
<textarea type="text" class="form-control"  rows="5" id="editor1"  name="txtcontent" rows="10">
'.$post[2].'
</textarea>
<span id="contenterr" class="text-danger"></span> 
</div>
<div class="form-group">
<span id="template_id">Template id: '.$post[5].'</span><span><button class="ml-1 mb-1 btn btn-outline-light" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fas fa-pencil-alt text-success"></i></button></span>
<div class="collapse" id="collapseExample">
<div class=" ml-1 card-body row">
<div class="col-md-12 row" style="height: 200px;">
<div class="col-md-3" id="changetemplate">
</div>
<div class="col-md-8 showtemplate" style="height: 200px;">
<iframe src="../../'.$template.'" id="iframe" height="400" width="1000" ></iframe>
</div>
</div> 
</div>
</div>
<input type="hidden" name="templateid" id="templateid" value="'.$post[5].'">
</div>
<input type="hidden" name="idpost" id="idpost" value="'.$post[0].'">
</form>
</div>';
$file = file_get_contents(dirname(__DIR__,2)."/".$temp,'r+');
$newtemplate = str_replace('<p class="formcreatepost"></p>',$edit,$file);
echo $newtemplate;
preg_match_all('~<a(.*?)href="([^"]+)"(.*?)>~', $newtemplate, $matches);
;?>
<div id="contenttemplate">
  <?php 
  foreach ($templates as $key => $value) {
    if ($value['id'] == $record[5]) {
      echo '<input type="radio" id="temp'.$value['id'].'" name="template" class="template" value="'.$value['id'].'" checked><label for="temp'.$value['id'].'">Template '.($key+1).' </label><br>';
    }else{
      echo '<input type="radio" id="temp'.$value['id'].'" name="template" class="template" value="'.$value['id'].'"><label for="temp'.$value['id'].'">Template '.($key+1).' </label><br>';
    }
  }
  ?>
</div>
<input type="hidden" value="<?php echo $temp; ?>" id="temp">
<!-- Modal Preview -->
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="content" style="height: 500px; overflow-y: auto;"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>  
<!-- Modal CheckLink-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Check Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php  
        foreach ($matches[2] as $key => $value) {
          if ($value != 'index.php' && $value != '/') {
            if ($postcontroller->checkLink($value)) {
              echo "<p>".$value." => Active</p>";
            }else{
              echo "<p>".$value." => Inactive</p>";
            }
          }
        }
        ?>
      </div>
    </div>
  </div>
</div> 
<script src="/assets/ckeditor/ckeditor.js"></script>
<script>CKEDITOR.replace('editor1');
$("#changetemplate").html($("#contenttemplate").html());
$("#contenttemplate").hide()
$( ".template" ).change(function() {
  var id = $(this).val(); 
  $.ajax({
    method: "POST",
    url: "/views/posts/gettemplate.php",
    data:{id: id } ,
    success : function(response){
      //console.log(response);
      $("#iframe").attr("src","../../"+response);
    }
  });
  $("#template_id").html("Template id: "+id);
  $("#templateid").val(id);
});
$(".preview").click(function(){
  var content = CKEDITOR.instances['editor1'].getData();
  var temp = $("#temp").val();
  $.ajax({
    method: "POST",
    url: "/views/posts/previewedit.php",
    data:{content: content, temp: temp } ,
    success : function(response){
      $(".content").html(response);
    }
  });
});
</script>
<?php 
 ?>