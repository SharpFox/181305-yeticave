<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('data.php');

$connect = connectDB();

if(!$connect) {
    $title = 'Вход';
    $isMainPage = false;
    
    $navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);
    $errorContent = renderTemplate('error.php', ['errorMessage' => mysqli_connect_error()]);
    
    $layoutContent = renderLayout($errorContent, $navContent, $title, $userAvatar);
    
    print($layoutContent);

    exit();
}
?>