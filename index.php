<?php
require_once('functions.php');
require_once('data.php');

// установка часового пояса и получение времени
date_default_timezone_set('Europe/Moscow');
$lotTimeRemaining = "00:00";
$tomorrow = strtotime('tomorrow midnight');
$now = strtotime('now');
$lotTimeRemaining = gmdate("H:i", ($tomorrow - $now));

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

$lotsItemVar = [
    'goodsContent' => $goodsContent,
    'lotTimeRemaining' => $lotTimeRemaining
];

$lotsItemContent = toRenderTemplate('lots-item.php', $lotsItemVar);

$indexVar = [ 
    'goodsCategory' => $goodsCategory,
    'lotsItemContent' => $lotsItemContent    
];

$indexContent = toRenderTemplate('index.php', $indexVar);

$layoutVar = [ 
    'content' => $indexContent,
    'navigationMenu' => $navContent,
    'title' => 'Главная',
    'isMainPage' => true,
    'isAuth' => $isAuth,
    'userName' => $userName,
    'userAvatar' => $userAvatar
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>