<?php
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
$goodsContents = [
    [
        'name' => htmlspecialchars("2014 Rossignol District Snowboard"),
        'category' => "Доски и лыжи",
        'cost' => htmlspecialchars(10999),
        'url' => "img/lot-1.jpg"
    ],
    [
        'name' => htmlspecialchars("DC Ply Mens 2016/2017 Snowboard"),
        'category' => "Доски и лыжи",
        'cost' => htmlspecialchars(159999),
        'url' => "img/lot-2.jpg"
    ],
    [
        'name' => htmlspecialchars("Крепления Union Contact Pro 2015 года размер L/XL"),
        'category' => "Крепления",
        'cost' => htmlspecialchars(8000),
        'url' => "img/lot-3.jpg"
    ],
    [
        'name' => htmlspecialchars("Ботинки для сноуборда DC Mutiny Charocal"),
        'category' => "Ботинки",
        'cost' => htmlspecialchars(10999),
        'url' => "img/lot-4.jpg"
    ],
    [
        'name' => htmlspecialchars("Куртка для сноуборда DC Mutiny Charocal"),
        'category' => "Одежда",
        'cost' => htmlspecialchars(7500),
        'url' => "img/lot-5.jpg"
    ],
    [
        'name' => htmlspecialchars("Маска Oakley Canopy"),
        'category' => "Разное",
        'cost' => htmlspecialchars(5400),
        'url' => "img/lot-6.jpg"
    ]
];

require_once('functions.php');

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