<?php
require_once('functions.php');
require_once('lot_data.php');

// аутентификация
$is_auth = (bool) rand(0, 1);
$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

// установка часового пояса и получение времени
date_default_timezone_set('Europe/Moscow');
$lot_time_remaining = "00:00";
$tomorrow = strtotime('tomorrow midnight');
$now = strtotime('now');
$lot_time_remaining = gmdate("H:i", ($tomorrow - $now));

// данные товаров
$goodsCategory = ["Доски и лыжи","Крепления","Ботинки","Одежда","Инструменты","Разное"];

$indexVar = [ 
    'goodsCategory' => $goodsCategory,
    'goodsContents' => $goodsContents,
    'lot_time_remaining' => $lot_time_remaining
];

$indexContent = toRenderTemplate('index.php', $indexVar);

$layoutVar = [ 
    'content' => $indexContent,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>