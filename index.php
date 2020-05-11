<?php
//header('Location: /views/posts/index.php');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_arr = explode('/', $request);
$id = $request_arr[count($request_arr)-1];
$post = '/views/posts/';
$template = '/views/templates/';
$user = '/views//users/';
$organization = '/views//organizations/';
$autolink = '/views//autolinks/';
switch ($request) {
    case '/' :
        require __DIR__.$post.'index.php';
        break;
    case '/post' :
        require __DIR__.$post.'index.php';
        break;
    case '/post/create' :
        require __DIR__.$post.'create.php';
        break;
    case '/post/edit/'.$id :
        require __DIR__.$post.'edit.php';
        break;
    case '/post/version/'.$id :
        require __DIR__.$post.'version.php';
        break;
    case '/post/related/'.$id :
        require __DIR__.$post.'related.php';
        break;
    case '/post/pageorder/'.$id :
        require __DIR__.$post.'pageorder.php';
        break;                        
    case '/template' :
        require __DIR__.$template.'index.php';
        break;
    case '/template/create' :
        require __DIR__.$template.'create.php';
        break;
    case '/template/detail/'.$id :
        require __DIR__.$template.'detail.php';
        break;
    case '/login' :
        require __DIR__.$post.'login.php';
        break; 
    case '/user' :
        require __DIR__.$user.'index.php';
        break;
    case '/user/create' :
        require __DIR__.$user.'create.php';
        break;  
    case '/organization' :
        require __DIR__.$organization.'index.php';
        break;  
    case '/organization/create' :
        require __DIR__.$organization.'create.php';
        break;
    case '/autolink' :
        require __DIR__.$autolink.'index.php';
        break;
    case '/autolink/create' :
        require __DIR__.$autolink.'related.php';
        break;      
    case '/autolink/pageorder' :
        require __DIR__.$autolink.'pageorder.php';
        break;
    case '/autolink/edit/'.$id :
        require __DIR__.$autolink.'related.php';
        break;                               
    default:
        http_response_code(404);
        require __DIR__.'/views/404.php';
        break;
}