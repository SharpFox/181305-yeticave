<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('data.php');

define("IP_MYSQL", 'localhost');
define("IP_MYSQL_USERNAME", 'root');
define("IP_MYSQL_PASSWORD", '');
define("IP_MYSQL_DATABASENAME", 'yeticave_181305');
define("IP_MYSQL_PORT", '');

$connectMySQL = mysqli_connect(IP_MYSQL, IP_MYSQL_USERNAME, IP_MYSQL_PASSWORD, IP_MYSQL_DATABASENAME, IP_MYSQL_PORT);

if(!$connectMySQL) {
    $title = 'Вход';
    $isMainPage = false;
    
    $navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);
    $errorContent = renderTemplate('error.php', ['errorMessage' => mysqli_connect_error()]);
    
    $layoutContent = renderLayout($errorContent, $navContent, $title, $isMainPage, $userAvatar);
    
    print($layoutContent);

    exit();
}
?>