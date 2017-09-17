<?php
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