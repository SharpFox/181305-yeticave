<?php
require_once('functions.php');
require_once('data.php');

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

$addVar = [
    'goodsCategory' => $goodsCategory,
    'navigationMenu' => $navContent
];

$addContent = toRenderTemplate('add.php', $addVar);

$layoutVar = [ 
    'content' => $addContent,
    'navigationMenu' => $navContent,
    'title' => 'Добавление лота',
    'isMainPage' => false,
    'isAuth' => $isAuth,
    'userName' => $userName,
    'userAvatar' => $userAvatar
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>