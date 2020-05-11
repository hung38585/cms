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
        <li class="nav-item">
          <a class="nav-link" href="/post">Post</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/template">Template</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/user">User</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/organization">Organization</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/autolink">Autolink</a>
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