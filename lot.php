<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('data.php');

identifyTypeVarForlegalizationVarSymbols($goodsCategory);
identifyTypeVarForlegalizationVarSymbols($goodsContent);
identifyTypeVarForlegalizationVarSymbols($lotDefaultDescription);
identifyTypeVarForlegalizationVarSymbols($defaultRateEndTime);

$goodsItem = isset($_GET['id']) ? $_GET['id'] : null;

printErrorInfoNotFound($goodsItem, $goodsContent);

$title = $goodsContent[$goodsItem]['name'];
$isMainPage = false;
$descriptionDefaulItem = 0;

$navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);

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

$lotContent = renderTemplate('lot.php', $lotVar);
$layoutContent = renderLayout($lotContent, $navContent, $title, $isMainPage, $userAvatar);
    
print($layoutContent);
?>