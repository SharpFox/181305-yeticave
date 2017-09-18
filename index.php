<?php
require_once('functions.php');
require_once('data.php');

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

$lotsItemVar = [
    'goodsContent' => $goodsContent
];

$lotsItemContent = toRenderTemplate('lots-item.php', $lotsItemVar);

$indexVar = [ 
    'goodsCategory' => $goodsCategory,
    'lotsItemContent' => $lotsItemContent    
];

$indexContent = toRenderTemplate('index.php', $indexVar);

$userContent = toRenderTemplate('user-menu.php', getUserMenuVar($userAvatar));

$layoutVar = [ 
    'content' => $indexContent,
    'navigationMenu' => $navContent,
    'title' => 'Главная',
    'isMainPage' => true,
    'userMenu' => $userContent
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>