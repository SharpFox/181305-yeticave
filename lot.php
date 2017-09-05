<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('data.php');

$goodsItem = isset($_GET['id']) ? $_GET['id'] : null;
toPrintErrorInfo($goodsItem, $goodsContent);

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

$lotVar = [ 
    'goodsContent' => $goodsContent,
    'goodsItem' => $goodsItem,
    'navigationMenu' => $navContent,
    'bets' => $bets
];

$lotContent = toRenderTemplate('lot.php', $lotVar);

$layoutVar = [ 
    'content' => $lotContent,
    'navigationMenu' => $navContent,
    'title' => $goodsContent[$goodsItem]['name'],
    'isMainPage' => false,
    'isAuth' => $isAuth,
    'userName' => $userName,
    'userAvatar' => $userAvatar
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>