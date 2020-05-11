<?php
include_once dirname(__DIR__,2)."/config/config.php";
include_once dirname(__DIR__,2)."/models/connectdb.php";
include_once dirname(__DIR__,2)."/controllers/postcontroller.php";
include_once dirname(__DIR__,2)."/controllers/templatecontroller.php";
include_once dirname(__DIR__,2)."/controllers/organizationcontroller.php";
include_once dirname(__DIR__,2)."/views/shared/header.php";
include_once dirname(__DIR__,2)."/views/posts/pagelist.php";
include_once dirname(__DIR__,2)."/models/path.php";
if (!isset($_SESSION['username'])){
  header('Location: '.domain.'/login');
}
$postcontroller = new postcontroller();
$organ = new organizationcontroller();
$temp = new templatecontroller();
$path = new path();
$level = $postcontroller->getLevelUser();
$listtempid = '';
if ($level == '2') {
  $deptcode = $postcontroller->getDeptcode();
  $listtempid = $organ->getTemplateId($deptcode);
  $listtempid = $listtempid[0]['template_id'];
  $foldername = $organ->getFolder($deptcode);
  $foldername = $foldername[0]['folder'];
}
$total_records = $postcontroller->totalrecords();
$templates = $temp->get_list_template($listtempid);
$posts = $postcontroller->index(); 
$dir = dirname(__DIR__,2)."/upload/";
if (isset($_SESSION['username'])) {
  $post = new postcontroller();
  if (isset($_POST['upload'])) {
    $ftp = new postcontroller();
    $ftp = $ftp->up_load();
  }
  if (isset($_POST['deleteall'])) {
    $post->deleteall();
  }
  if (isset($_POST['unpublic'])) {
    $post->un_public();
  }
  if (isset($_POST['approve'])) {
    $post->approve();
  }
  if (isset($_POST['denail'])) {
    $post->denail();
  }
} 
$user_id = $postcontroller->getIdUser(); 
$page = '';
$search = '';
$show = '';
$status = '';
if (isset($_GET['page'])) {
  $page = "page=".$_GET['page']."&";
}
if (isset($_GET['search'])) {
  $search = "search=".$_GET['search'];
} 
if (isset($_GET['show'])) {
  $show = '&show='.$_GET['show'];
}
if (isset($_GET['status'])) {
  $status = '&status='.$_GET['status'];
}
    //fix bug url
$namepage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (isset($_GET['show'])) {
  if ($_GET['show'] != limit  && $_GET['show'] != limit2 && $_GET['show'] != limit3) {
    if (isset($_GET['search'])) {
      $search = "search=".$_GET['search']."&";
    } 
    header('Location: '.domain.$namepage.'?'.$page.$search.'show=5'.$status);
  }
}
if (isset($_GET['status'])) {
  if ($_GET['status'] != status_1  && $_GET['status'] != status_2 && $_GET['status'] != status_3) {
    if (isset($_GET['show'])) {
      if (isset($_GET['search'])) {
        $show = "&show=".$_GET['show'];
      }else{
        $show = "show=".$_GET['show'];
      }
    } 
    if (isset($_GET['search']) || isset($_GET['show']) || isset($_GET['page'])) {
      header('Location: '.domain.$namepage.'?'.$page.$search.$show);
    }else{
      header('Location: '.domain);
    }
  }
}  
?>
<title>Post</title>
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
<body>
  <div class="container">
    <div class="card mr-4 mt-3">
      <div class="card-header">
        <div class="col-md-12 row">
          <b class="h4 col-md-1"> Post</b>
          <form method="get" class="col-md-9 row" onsubmit="filter()">
            <input type="text" class="form-control col-md-3" name="search" id="keyword" value="<?php if (isset($_GET['search'])) {echo htmlspecialchars($_GET['search'],ENT_QUOTES); }  ?>" placeholder="Search by title!" onpaste="checkkeyword()" onkeypress="checkkeyword()">
            <!-- Show -->
            <select class="custom-select ml-1" id="show" name="show" style="width: 60px;">
              <option value="<?php echo limit; ?>"><?php echo limit; ?></option>
              <option value="<?php echo limit2; ?>"><?php echo limit2; ?></option>
              <option value="<?php echo limit3; ?>"><?php echo limit3; ?></option>
            </select>
            <!-- Status -->
            <?php if ($level == '1' || $level == '2' || $level == '3'): ?>
              <select class="custom-select ml-1" id="status" name="status" style="width: 100px;">
                <option value="">Status!</option>
                <option value="<?php echo status_1; ?>"><?php echo status_1; ?></option>
                <option value="<?php echo status_2; ?>"><?php echo status_2; ?></option>
                <option value="<?php echo status_3; ?>"><?php echo status_3; ?></option>
              </select>
            <?php endif ?>
          </form>
          <!-- Add New -->
          <?php if ($level == '1' || $level == '2'): ?>
            <button type="button" class="btn btn-outline-info mr-1" data-toggle="modal" data-target="#addnew">Add <i class="fas fa-plus-square"></i></button>
          <?php endif ?>
          <!-- Delete -->
          <?php if ($level == '1' || $level == '2'): ?>
            <button class="btn btn-outline-danger" type="button"  id="del"><i class="far fa-trash-alt" ></i></button> 
          <?php endif ?>
          <!-- Upload -->
          <?php if ($level == '1' || $level == '3'): ?>
            <button class="btn btn-outline-info ml-1 " type="button"  id="up"><i class="fas fa-cloud-upload-alt"></i></button> 
          <?php endif ?>  
        </div>
      </div>
      <div class="card-body row">
        <!-- List post -->
        <table class="table table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" class="" id="checkAll"></th>
              <!-- <th scope="col">ID</th> -->
              <th>Title</th>
              <th>Status</th>
              <th>Created at</th>
              <th>Action</th>
              <?php
              if ($level == '2' || $level == '1') {
                ?>
                <th>Unpublic</th>
                <th>Versions</th>
                <th>Share</th>  
                <?php
              }else{
                ?>
                <th></th>
                <th></th>
                <th></th>
                <?php
              }
              ?>
            </tr>
          </thead>
          <tbody>
            <form method="post">        
              <?php
              if (!$posts) {
                ?>
                <td></td>
                <td></td>
                <td></td>
                <td width="350" class="ml-5"><span class="ml-5"></span><span class="ml-5"></span><span class="ml-5" >Database is empty!</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <?php
              }
              foreach ($posts as $post) { 
                if ($post['urlfolder']) {
                  $folder = $path->getfolder($post['urlfolder']);
                  $name = $path->getname($post['urlfolder']);
                  $filename = $folder[$name];
                  unset($folder[$name]);
                  $url = '';
                  foreach ($folder as $key => $value) {
                    $url .= $value.'/';
                  }
                  $link = pubdomain.'/'.$url.$filename."/".$filename.'.html';
                }else{
                  $link = pubdomain.'/'.$post['id']."/".$post['id'].'.html';
                }
                ?>
                <tr>
                  <td>
                    <input type="checkbox" name="id[]" value="<?php echo $post['id']; ?>"> 
                  </td>
                  <!-- <td><?php //echo $post['id']; ?> </td> -->
                  <td width="300">
                    <?php
                    if ($post['status'] == '2' || $post['status'] == '3') {
                      ?>
                      <a href="<?php echo $link; ?>" target="_blank"><?php echo $post['title']; ?></a>
                      <?php
                    }else{
                      echo $post['title'];
                    } 
                    ?>
                  </td>
                  <td>
                    <?php 
                    switch ($post['status']) {
                      case '1':
                      echo status_1; 
                      break;
                      case '2':
                      echo status_2; 
                      break;
                      case '3':
                      echo status_3; 
                      break; 
                      case '7':
                      echo status_5; 
                      break;
                      case '6':
                      echo status_6; 
                      break;  
                      case '5':
                      echo status_7; 
                      break;
                      case '4':
                      echo status_8; 
                      break;
                      case '8':
                      echo status_9; 
                      break;        
                      default:
                        # code...
                      break;
                    }
                    ?>
                  </td>
                  <td ><?php echo $post['created_at']; ?></td>
                  <td width="250">
                    <?php if ($level == '1' || $level == '2'): ?>
                      <a class="btn btn-outline-success" href="/post/edit/<?php echo $post['id']; ?>">
                        <i class="far fa-edit"></i>
                      </a>
                    <?php endif ?>
                    <?php if ($level == '4' || $level == '1' && $post['status'] != '1' && $post['status'] != '2' && $post['status'] != '3' && $post['status'] != '8'): ?>
                      <button type="submit" class="btn btn-outline-success" name="approve" value="<?php echo $post['id']; ?>"><i class="far fa-check-square"></i></button>
                      <button type="submit" class="btn btn-outline-danger" name="denail" value="<?php echo $post['id']; ?>"><i class="far fa-window-close"></i></button>
                    <?php endif ?>
                    <?php if ($post['status'] != 2): ?>
                      <button type="button" class="btn btn-outline-info preview" data-toggle="modal" data-target=".bd-example-modal-xl" value="<?php echo $post['id']; ?>" ><i class="fas fa-eye"></i></button> 
                    <?php endif ?>
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
                  </td>
                  <!-- un public -->
                  <td>
                    <?php if ($post['status'] == 2 && $level == '1' ) {
                      echo '<button type="submit" class="btn btn-outline-danger unpublic" name="unpublic" value="'.$post['id'].'"><i class="fas fa-trash-restore-alt"></i></button>';
                    } 
                    ?>                    
                  </td>
                  <!-- Version -->
                  <?php  
                  if (isrollback == "on") {
                    ?>
                    <td>
                      <?php if ($level == '1' || $level == '2'): ?>
                        <a class="btn btn-outline-dark" href="/post/version/<?php echo $post['id']; ?>"><i class="fas fa-list-ul"></i></a>
                      <?php endif ?>
                    </td>
                    <?php
                  }else{
                    echo "<td></td>";
                  }
                  //share  
                  if (isshare == "on" && $post['status'] == 2 && $level == '1') {
                    ?>
                    <td>
                      <button type="button" class="shareBtn btn btn-outline-info" value="<?php echo $post['urlfolder']; ?>"><i class="fas fa-share-square"></i></button>
                    </td>
                    <?php
                  }else{
                    echo "<td></td>";
                  }
                  ?>
                </tr>  
              <?php } 
              ?>
              <!-- Modal Delete-->
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
              <!-- End Modal delete-->
              <!-- Modal Upload-->
              <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                  <div class="modal-content">
                    <div class="modal-body">
                      <h5 class="modal-title" id="exampleModalLabel">Upload</h5>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                      <button class="btn btn-outline-info" name="upload">Upload</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Modal Upload-->
            </form>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Modal template-->
    <div class="modal fade bd-example-modal-lg" id="addnew" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalScrollableTitle">Select template</h5>
            <button type="button" class="close closeaddnew" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="POST" action="/post/create" onsubmit = "return validateForm();">
              <div class="row">
                <div class="col-md-12 row">
                  <label class="font-weight-bold ml-4 mr-3">Title:</label>
                  <input type="text" class="form-control col-md-9" id="title"  name="title" onpaste="checktitle()" onkeypress="checktitle()" > 
                  <input type="submit" class="btn btn-outline-info ml-1 col-md-1 next" name="changed" value="Next">
                  <span id="titleerr" class="text-danger ml-4"></span> 
                </div>
                <div class="col-md-12">
                  <div class="col-md-12 row">
                    <button type="button" class="btn btn-outline-dark mt-2 mb-1" data-toggle="modal" data-target="#changefolder" style="width: 120px;">URL Folder</button>
                    <input type="hidden" id="path" name="path" class="form-control col-md-4" readonly="readonly">
                    <span id="showpath"  class="mt-3 ml-2 mr-2"></span>   
                    <a href="javascript:void(0)" id="clear" class="mt-3 text-danger font-weight-bold" style="text-decoration: none;">X</a>
                    <span id="patherr" class="text-danger mt-3 ml-1"></span>
                  </div>
                </div>
                <div class="col-md-3">
                  <?php  
                  foreach ($templates as $key => $value) {
                    if ($key == 0) {
                      echo '<input type="radio" id="temp'.$value['id'].'" name="template" class="template" value="'.$value['id'].'" checked><label for="temp'.$value['id'].'">Template '.($key+1).' </label><br>';
                    }else{
                      echo '<input type="radio" id="temp'.$value['id'].'" name="template" class="template" value="'.$value['id'].'"><label for="temp'.$value['id'].'">Template '.($key+1).' </label><br>';
                    }
                  }
                  ?>
                  <hr>
                  <input type="checkbox" name="isfb" id="isfb" value="1">
                  <label for="isfb">Post Facebook</label>
                </div>
              </form>  
              <div class="showtemplate col-md-8" style=" height: 300px;">
                <iframe src="<?php echo $templates[0]['urltemplate']; ?>" id="iframe" height="500" width="1000" ></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal URL Folder -->
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
              $dem = 0;
              foreach ($folder as $key => $value) {
                if ($key>2) {
                  if (!strpos($value,'.html')) {
                    if ($value != 'document' && $value != 'images' && $value != 'files') {
                      $name = "'".$value."'";
                      if ($level == '2' && $foldername != '/') {
                        if ($value == $foldername) {
                          echo '<button onclick="selectfolder('.$name.',this.id)" id="firstfolder" class="btn" value="'.$value.'" style="margin: 0; height: 20px; padding: 0"><h6><i class="fas fa-folder"></i> '.$value.'</h6></button><br>';
                          $dem++;
                        }
                      }else{
                        echo '<button onclick="selectfolder('.$name.',this.id)" id="firstfolder" class="btn" value="'.$value.'" style="margin: 0; height: 20px; padding: 0"><h6><i class="fas fa-folder"></i> '.$value.'</h6></button><br>';
                      }
                    }
                  }else{
                    echo '<h6 style="margin:0">'.$value.'</h6>';
                  } 
                }
              }
              if ($dem == 0 && $level == '2') {
                $name = "'".$foldername."'";
                echo '<button onclick="selectfolder('.$name.',this.id)" id="firstfolder" class="btn" value="'.$foldername.'" style="margin: 0; height: 20px; padding: 0"><h6><i class="fas fa-folder"></i> '.$foldername.'</h6></button><br>';
                mkdir(dirname(__DIR__,2)."/upload/".$foldername); 
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
    <!-- PageList -->
    <?php  
    if (isset($_GET['search'])) {
      $search = '&search='.$_GET['search'];
    }
    if (isset($_GET['show'])) {
      $show = '&show='.$_GET['show'];
    }
    $pagelist = new pagelist();
    $pagelist = $pagelist->pagination($namepage,$total_records,$search,$show,$status);
    ?>
  </div>  
</body>
<script type="text/javascript">
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
  $("#up").click(function () {
    var ischeck = false;
    $("input:checkbox:checked").each(function () {
      if ($(this).val()) {
        ischeck = true;
      }
    });
    if (!ischeck) {
      alert('Please choose!');
    }else{
      $('#exampleModalScrollable').modal('show');
    }
  });
  $("#checkAll").click(function () {
    $('input:checkbox').not(this).prop('checked', this.checked);
  });
  $(".preview").click(function(){
    var id = $(this).val();  
    $.ajax({
      method: "POST",
      url: "/views/posts/preview.php",
      data:{id: id } ,
      success : function(response){
        //console.log(response);
        $(".content").html(response);
      }
    });
  });
  $('#keyword').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
      var keyword = $("#keyword").val()
      var format = /[@#$%^&*()+\=\[\]{}\\|<>\/?]/;
      var newkey = '';
      keyarray = keyword.split("");
      for (var i = 0; i < keyarray.length; i++) {
        if (keyarray[i] != ' ' || keyarray[i+1] != ' ') {
          newkey= newkey+keyarray[i];
        }
      }
      $("#keyword").val(newkey);
      if (format.test(keyword)) {
        alert("Don't enter special characters!");
        return false;
      } 
      if (keyword.length == 0) {
        alert("Please enter keywords");
        return false;
      }
      if (keyword == 0) {
        alert("Please enter keywords");
        $("#keyword").val('');
        return false;
      }  
      if ($("#keyword").val() == 0) {
        $("#keyword").val('');
      }else{
        $("#keyword").val($("#keyword").val().trim());
      } 
    }
  });
  function checkkeyword() {
    var keyword = document.getElementById("keyword").value;
    if (keyword.length >49) {
      alert('Max keywords length is 50');
    }  
  }
  function checktitle() {
    var keyword = document.getElementById("title").value;
    if (keyword.length >100) {
      alert('Max keywords length is 100');
    }  
  }
  $(".closeaddnew").click(function(){
    $(".modal-backdrop").remove();
  });
  $( ".template" ).change(function() {
    var id = $(this).val();  
    $.ajax({
      method: "POST",
      url: "/views/posts/gettemplate.php",
      data:{id: id } ,
      success : function(response){
        //console.log(response);
        //$(".showtemplate").html(response);
        $("#iframe").attr("src","../../"+response);
      }
    });
  });
  <?php  
  if (isset($_GET['status'])) {
    ?>
    $("#status").val("<?php echo $_GET['status']; ?>");
    <?php
  }
  if (isset($_GET['show'])) {
    ?>
    $("#show").val("<?php echo $_GET['show']; ?>");
    <?php
  }
  if (isset($_GET['search'])) {
    $search = $search;
  }
  ?>
  $("select#show").change(function(){
    <?php 
    if (!isset($_GET['search'])) {
      ?>
      document.getElementById("keyword").setAttribute("disabled", true);
      <?php
    }
    ?>
    this.form.submit();
  });
  $("select#status").change(function(){
    <?php 
    if (!isset($_GET['search'])) {
      ?>
      document.getElementById("keyword").setAttribute("disabled", true);
      <?php
    }
    if (!isset($_GET['show'])) {
      ?>
      document.getElementById("show").setAttribute("disabled", true);
      <?php  
    }
    ?>
    this.form.submit();
  });
  function filter() {
    document.getElementById("status").setAttribute("disabled", true);
    document.getElementById("show").setAttribute("disabled", true);
  }
  // setInterval(function(){ alert("Hello"); }, 3000);
  $('.unpublic').click(function(e){
    if (!confirm('Unpublic this page!')) {
      e.preventDefault();
    }
  });
</script>
<?php  
include_once dirname(__DIR__,2)."/common/scriptcreate.php";
?>      
<script src="http://connect.facebook.net/en_Us/all.js"></script>
<script>
  $('.shareBtn').click(function(){
    var link = "<?php echo pubdomain; ?>" +"/";
    var url = $(this).val();
    link = link+url;
    var name = link.split('/');
    namepage = "/"+name[name.length-1];
    FB.ui({
      display: 'popup',
      method: 'share',
      href: link+namepage+'.html',
    }, function(response){});
  });
  $(document).ready(function()
  {
    $.getScript("http://connect.facebook.net/en_US/all.js#xfbml=1", function () {
      FB.init({ appId: <?php echo app_id; ?>, status: true, cookie: true, xfbml: true });
    });
  });
</script>