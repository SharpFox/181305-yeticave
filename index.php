<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$title = 'Главная';
$isMainPage = true;

identifyTypeVarForlegalizationVarSymbols($goodsCategory);
identifyTypeVarForlegalizationVarSymbols($goodsContent);

$navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);
$lotsItemContent = renderTemplate('lots-item.php', ['goodsContent' => $goodsContent]); 

$indexVar = [ 
    'goodsCategory' => $goodsCategory,
    'lotsItemContent' => $lotsItemContent    
];

$indexContent = renderTemplate('index.php', $indexVar);
$layoutContent = renderLayout($indexContent, $navContent, $title, $isMainPage, $userAvatar);

print($layoutContent);
?>