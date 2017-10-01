<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('data.php');

define("IP_MYSQL", 'localhost');
define("USERNAME_MYSQL", 'root');
define("PASSWORD_MYSQL", '');
define("DATABASENAME_MYSQL", 'yeticave_181305');
define("PORT_MYSQL", NULL);

$connectMySQL = mysqli_connect(IP_MYSQL, USERNAME_MYSQL, PASSWORD_MYSQL, DATABASENAME_MYSQL, PORT_MYSQL);

if (!mysqli_set_charset($connectMySQL, "utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", mysqli_error($connectMySQL));
    
    exit();
} 

if(!$connectMySQL) {
    $title = 'Вход';
    $isMainPage = false;
    
    $navVar = [
        'categories' => $categories,
        'currentCategoryId' => isset($_GET['category-id']) ? intval($_GET['category-id']) : 0   
    ];
    $navContent = renderTemplate('nav.php', $navVar);
    
    $errorContent = renderTemplate('error.php', ['errorMessage' => mysqli_connect_error()]);
    
    $layoutContent = renderLayout($errorContent, $navContent, $title, $isMainPage);
    
    print($layoutContent);

    exit();
}

startSession();
?>