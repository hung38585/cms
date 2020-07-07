<?php 
include_once dirname(__DIR__,2)."\config\config.php";
include_once dirname(__DIR__,2)."\controllers\postcontroller.php";
$post = new postcontroller();
$level = $post->getLevelUser();
if (isset($_POST['logout'])) {
  $post->logout();
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" >
  
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav col-md-9">
        <?php if ($level == '1'): ?>
        <li class="nav-item post">
          <a class="nav-link" href="/post">Post</a>
        </li>
        <li class="nav-item template">
          <a class="nav-link " href="/template">Template</a>
        </li>
        <li class="nav-item user">
          <a class="nav-link " href="/user">User</a>
        </li>
        <li class="nav-item organization">
          <a class="nav-link " href="/organization">Organization</a>
        </li>
        <li class="nav-item autolink">
          <a class="nav-link" href="/autolink">Autolink</a>
        </li>
        <li class="nav-item flow">
          <a class="nav-link" href="/flow">Flow</a>
        </li>
        <li class="nav-item area">
          <a class="nav-link " href="/ara">Area</a>
        </li>
        <li class="nav-item banner">
          <a class="nav-link" href="/banner">Banner</a>
        </li>
        <li class="nav-item feedback">
          <a class="nav-link" href="/feedback">Feedback</a>
        </li>
        <li class="nav-item category">
          <a class="nav-link" href="/category">Category</a>
        </li>
        <?php endif ?>
      </ul>
      <div class="col-md-3">
        <p class="float-right mt-2">
        <?php   
          if (isset($_SESSION['username']) &&$_SESSION['username']){
            echo '<form method=Post><button name="logout" class="btn" style="float:right;"><i class="fas fa-sign-out-alt"></i></button><span class="mt-2" style="float:right;"> Xin chào : '.$_SESSION['username'].'</span> </form>';
          }
          ?>
        </p>
      </div>
    </div>
  </nav>
</html>
<script>
  <?php 
  switch ($request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case '/post':
      ?>
      $("li.post").addClass("active");
      <?php
      break;
    case '/template':
      ?>
      $("li.template").addClass("active");
      <?php
      break;
    case '/user':
      ?>
      $("li.user").addClass("active");
      <?php
      break;
    case '/organization':
      ?>
      $("li.organization").addClass("active");
      <?php
      break;
    case '/autolink':
      ?>
      $("li.autolink").addClass("active");
      <?php
      break;
    case '/flow':
      ?>
      $("li.flow").addClass("active");
      <?php
      break;
    case '/ara':
      ?>
      $("li.area").addClass("active");
      <?php
      break;
    case '/banner':
      ?>
      $("li.banner").addClass("active");
      <?php
      break;
    case '/feedback':
      ?>
      $("li.feedback").addClass("active");
      <?php
      break; 
    case '/category':
      ?>
      $("li.category").addClass("active");
      <?php
      break;                 
    default:
      # code...
      break;
  }
  ?>
</script>