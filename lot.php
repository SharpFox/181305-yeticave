<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('data.php');

$goodsItem = isset($_GET['id']) ? $_GET['id'] : null;
printErrorInfoNotFound($goodsItem, $goodsContent);

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

$descriptionDefaulItem = 0;

$lotVar = [ 
    'goodsContent' => $goodsContent,
    'goodsItem' => $goodsItem,
    'navigationMenu' => $navContent,
    'bets' => $bets,
    'lotDescription' => $lotDefaultDescription,
    'descriptionDefaulItem' => $descriptionDefaulItem,
    'rateEndTime' => $defaultRateEndTime,
    'isAuth' => isset($_SESSION['user'])
];

$lotContent = toRenderTemplate('lot.php', $lotVar);

$userContent = toRenderTemplate('user-menu.php', getUserMenuVar($userAvatar));

$layoutVar = [ 
    'content' => $lotContent,
    'navigationMenu' => $navContent,
    'title' => $goodsContent[$goodsItem]['name'],
    'isMainPage' => false,
    'userMenu' => $userContent
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>