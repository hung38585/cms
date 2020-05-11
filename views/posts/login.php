<?php 
include_once dirname(__DIR__,2)."/controllers/postcontroller.php";
include_once dirname(__DIR__,2)."/views/shared/layout.php";
$post = new postcontroller();
if (isset($_POST['login'])) {
    header('Location: '.domain.'/');
}
?>
<title>Login</title>
<div class="container  ">
    <div class="m-auto col-md-4">
        <h2 class="text-center">Login</h2>
        <form method="Post" onsubmit="return checklogin();">
            <div class=" form-group">
                <input type="text" class="form-control" placeholder="Enter your Username" name="txtUsername" id="username">
            </div> 
            <div class="form-group">
                <input type="password" placeholder="Enter your Password" class="form-control" name="txtPassword" id="password">
            </div>
            <div class=" form-group">
                <button class="col-md-12 btn btn-outline-danger" name="login" id="login">Login</button>
                <p id="err" class="text-danger mt-1 text-center"></p>
            </div> 
        </form>    
    </div>    
</div>
<script type="text/javascript">
    function checklogin(){
        var result = false;
        var username = $("#username").val();
        var password = $("#password").val(); 
        $.ajax({
            async: false,
            method: "POST",
            url: "/views/posts/checklogin.php",
            data:{
                username: username,
                password: password
            } ,
            success : function(response){
                if(response == "1")
                {
                    $("#err").html("Please enter your Username and Password");
                    result = false;
                }else{
                    if (response == "2") {
                        result = true;
                    }else{
                        $("#err").html("Username or Password is incorrect");
                        result = false;
                    }
                }
            }
        });  
        return result;       
    }    
</script>