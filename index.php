<?php
session_start();

require_once('functions.php');
require_once('data.php');

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

foreach ($goodsContent as $goodsContentKey => $product) {
    foreach($product as $productKey=> $info) {
        $goodsContent[$goodsContentKey][$productKey] = makeSymbolsLegal($info);    
    }
}

$lotsItemVar = [
    'goodsContent' => $goodsContent
];

$lotsItemContent = toRenderTemplate('lots-item.php', $lotsItemVar);

$indexVar = [ 
    'goodsCategory' => $goodsCategory,
    'lotsItemContent' => $lotsItemContent    
];

$indexContent = toRenderTemplate('index.php', $indexVar);

$userVar = [
    'isAuth' => isset($_SESSION['user']),
    'userName' => $_SESSION['user'],
    'userAvatar' => $userAvatar
];

$userContent = toRenderTemplate('user-menu.php', $userVar);

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