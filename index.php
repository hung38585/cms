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
$flow = '/views/flows/';
$ara = '/views/aras/';
$banner = '/views/banners/';
$feedback = '/views/feedbacks/';
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
    case '/flow' :
        require __DIR__.$flow.'index.php';
        break;
    case '/flow/create' :
        require __DIR__.$flow.'create.php';
        break; 
    case '/ara' :
        require __DIR__.$ara.'index.php';
        break;  
    case '/ara/create' :
        require __DIR__.$ara.'create.php';
        break;
    case '/ara/bannerorder/'.$id :
        require __DIR__.$ara.'bannerorder.php';
        break; 
    case '/ara/edit/'.$id :
        require __DIR__.$ara.'edit.php';
        break;      
    case '/banner' :
        require __DIR__.$banner.'index.php';
        break;  
    case '/banner/create' :
        require __DIR__.$banner.'create.php';
        break;  
    case '/banner/edit/'.$id :
        require __DIR__.$banner.'edit.php';
        break; 
    case '/auto_cron_feedback' :
        require __DIR__.$post.'auto_cron_feedback.php';
        break;  
    case '/feedback' :
        require __DIR__.$feedback.'index.php';
        break;                                                           
    default:
        http_response_code(404);
        require __DIR__.'/views/404.php';
        break;
}