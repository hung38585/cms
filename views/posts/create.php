<?php 
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/controllers/postcontroller.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/controllers/organizationcontroller.php";
include_once  dirname(__DIR__,2)."/models/path.php";
$post = new postcontroller();
$organ = new organizationcontroller();
$level = $post->getLevelUser();
if (!isset($_SESSION['username'])){
  header('Location: '.domain.'/login');
}
if ($level != '1' && $level != '2') {
  header('Location: '.domain.'/');
}
$listtempid = '';
if ($level == '2') {
  $deptcode = $post->getDeptcode();
  $listtempid = $organ->getTemplateId($deptcode);
  $listtempid = $listtempid[0]['template_id'];
}
$temp = new templatecontroller();
$path = new path();
$dir = dirname(__DIR__,2)."/upload/";
if (isset($_SESSION['username'])){
  if (isset($_POST['savepost'])) {
    $post->create();
  }
  $template = $post->get_urltemplate($_POST['template'],2);
  $templates = $temp->get_list_template($listtempid);
  $urlcs = $post->get_urltemplate($_POST['template'],3);
  $listfolder = scandir(dirname(__DIR__,2)."/".$urlcs);
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
?>
<title>Create</title>
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
</style>
<?php
if (isset($_POST['template'])) { 
  $create = '<button type="button" class="btn btn-primary" id="save"style="position: fixed; top: 40%; right: 1%; width: 78px;z-index: 1000;">Save</button>
  <a href="/" class="btn btn-danger" style="position: fixed; top: 46.5%; right: 1%;z-index: 1000;"><i class="mr-1 fas fa-arrow-left"></i>Back</a>
  <button type="button" class="btn btn-success preview" data-toggle="modal" data-target=".bd-example-modal-xl" style="position: fixed; top: 53%; right: 1%; width:77px;z-index: 1000;" >Preview</button>
  <div class="form-group row" >
  <div class="col-md-12">
  <h4 class="mt-5">'.$_POST['title'].'</h4>
  <input type="hidden" name="template_id" value="'.$_POST["template"].'">
  </div>
  </div>
  <form method="POST" onsubmit = "return validateForm();">
  <div class="form-group ">
  <label class="font-weight-bold">Content:</label>
  <textarea class="form-control" id="editor1" name="txtcontent" rows="10"></textarea>
  <span id="contenterr" class="text-danger"></span> 
  </div>
  ';
  $link =  file_get_contents(dirname(__DIR__,2)."/models/link.txt");
  $file = file_get_contents(dirname(__DIR__,2)."/".$template,'r+');
  $newtemplate = str_replace('<p class="formcreatepost"></p>',$create,$file);
  echo $newtemplate;
}
?>
<input type="hidden" value="<?php echo $template; ?>" id="temp">
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
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="savemodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Page Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-danger">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row mt-2">
          <label class="col-md-2 font-weight-bold">Title:</label>
          <input type="text" name="txttitle" id="title" class="form-control col-md-9" value="<?php echo $_POST['title'] ?>" onpaste="checktitle()" onkeypress="checktitle()">
          <span id="titleerr" class="text-danger col-md-11"></span> 
        </div>
        <div class="form-group row mt-2">
          <button type="button" class="btn btn-outline-dark mt-2 mb-1" data-toggle="modal" data-target="#changefolder" style="margin-left: 16.5%;">URL Folder</button>
          <input type="hidden" id="path" name="path" value="<?php echo $_POST['path']; ?>">
          <input type="hidden" name="isfb" value="<?php if(isset($_POST['isfb'])){echo $_POST['isfb'];}else{echo 0;} ?>">
          <input type="hidden" name="category_id" value="<?php echo $_POST['category_id']; ?>">
          <div class="col-md-7 mt-3" style="overflow-x: auto;">
            <span id="showpath"  class="ml-2 mr-2 col-md-9"><?php echo $_POST['path']; ?></span>  
            <a href="javascript:void(0)" id="clear" class="mt-3 text-danger font-weight-bold" style="text-decoration: none;">X</a> 
            <span id="patherr" class="text-danger mt-3 ml-1"></span>
          </div>
        </div>
        <div class="form-group row mt-2">  
          <label class="ml-3 font-weight-bold">Template: </label>
          <span class="ml-1 nametemplate"><?php echo $_POST['template']; ?></span>
          <input type="hidden" id="templateid" name="templateid" value="<?php echo $_POST['template']; ?>">
          <a class="mt-1 ml-2 text-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="fas fa-pencil-alt"></i></a>
          <div class="collapse col-md-12" id="collapseExample">
            <div class="card card-body row">
              <div class="row col-md-12">
                <div class="col-md-4">
                  <?php 
                  foreach ($templates as $key => $value) {
                    if ($value['id'] == $_POST['template']) {
                      echo '<input type="radio" id="temp'.$value['id'].'" name="template" class="template" value="'.$value['id'].'" checked><label for="temp'.$value['id'].'">Template '.($key+1).' </label><br>';
                    }else{
                      echo '<input type="radio" id="temp'.$value['id'].'" name="template" class="template" value="'.$value['id'].'"><label for="temp'.$value['id'].'">Template '.($key+1).' </label><br>';
                    }
                  }
                  ?>
                </div>
                <div class="col-md-8 showtemplate" style="height: 220px;">
                  <iframe src="../../<?php echo $template; ?>" id="iframe" height="440" width="1000" ></iframe>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="height: 50px;">
          <button type="submit" name="savepost" id="savepost" class="btn btn-outline-info m-auto">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="container">
  <!--Model Change foler-->
  <div class="modal fade" id="changefolder" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Select the path to save the file</h5>
          <button type="button" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body row">
          <button class="btn btn-outline-success ml-1" style="padding: 0 10px; height: 40px; font-size: 20px;" id="home"><b ><i class="fas fa-home"></i></b></button>
          <button onclick="back()" class="btn" style="height: 40px; padding: 0 10px; font-size:20px;"><i class="fas fa-long-arrow-alt-left text-success"></i></button>
          <input type="text"  id="urlfolder" class="form-control col-md-6 mr-1 ml-1" readonly="readonly" placeholder="Path">
          <input type="text" class="form-control mb-1 col-md-3" id="namefolder" placeholder="Name folder">
          <button class="btn btn-outline-success ml-1" style="padding: 0 5px; height: 40px; font-size: 20px;" id="create"><b >+<i class="fas fa-folder"></i></b></button>
          <div class="ml-3 col-md-3" style="border-right: 1px; height: 300px;  overflow-y: auto; overflow-x: hidden;">
            <h5 id="folder1" style="margin: 0;"></h5>
            <?php
            $folder = $path->getlistfolder($dir);
            foreach ($folder as $key => $value) {
              if ($key>2) {
                if (!strpos($value,'.html')) {
                  if ($value != 'document' && $value != 'images' && $value != 'files' && $value != 'cgi' && $value != 'csv') {
                    $name = "'".$value."'";
                    echo '<button onclick="selectfolder('.$name.',this.id)" id="firstfolder" class="btn" value="'.$value.'" style="margin: 0; height: 20px; padding: 0"><h6><i class="fas fa-folder"></i> '.$value.'</h6></button><br>';
                  }
                }else{
                  echo '<h6 style="margin:0">'.$value.'</h6>';
                } 
              }
            }  
            ?>
          </div>
          <div class="col-md-7 ml-3" style="border-left: 1px solid gray; height: 300px;  overflow-y: auto; overflow-x: hidden;" id="content">

          </div>  
        </div>
        <div class="modal-footer">
          <input type="text" class="form-control" id="filename" placeholder="Enter name file">
          <input type="submit" class="btn btn-outline-success" id="setpath"  value="OK" >
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/assets/ckeditor/ckeditor.js"></script>
<script>
  CKEDITOR.replace('editor1');
  $( ".template" ).change(function() {
    var id = $(this).val();  
    $(".nametemplate").html(id);
    $("#templateid").val(id);
    $.ajax({
      method: "POST",
      url: "/views/posts/gettemplate.php",
      data:{id: id } ,
      success : function(response){
        //console.log(response);
        $("#iframe").attr("src","../../"+response);
      }
    });
  });
  $("#save").click(function(){
    var content = CKEDITOR.instances['editor1'].getData();
    if (content == '') {
      $("#contenterr").text('Please enter your Content');
    }else{
      $('.bd-example-modal-lg').modal('show');
    }
  });
  function checktitle() {
    var keyword = document.getElementById("title").value;
    if (keyword.length >100) {
      alert('Max keywords length is 100');
    }  
  }
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
include_once dirname(__DIR__,2)."\common\scriptcreate.php";
?>