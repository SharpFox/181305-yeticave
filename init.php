<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('data.php');

$connectMySQL = mysqli_connect('localhost', 'root', '', 'yeticave_181305');

if(!$connectMySQL) {
    $title = 'Вход';
    
    $navContent = '';
    $errorContent = renderTemplate('error.php', ['errorMessage' => mysqli_connect_error()]);
    
    $layoutContent = renderLayout($errorContent, $navContent, $title);
    
    print($layoutContent);

    exit();
}
?>