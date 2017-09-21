<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('data.php');

printErrorInfoForbidden(isset($_SESSION['user']));

$title = 'Мои ставки';
$isMainPage = false;
$ratedContent = [];

identifyTypeVarForlegalizationVarSymbols($goodsCategory);
identifyTypeVarForlegalizationVarSymbols($goodsContent);

$navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);

if(isset($_COOKIE['bets'])) {
    $ratedContent = json_decode($_COOKIE['bets'], true);
}

$mylotsVar = [ 
    'ratedContent' => $ratedContent,
    'navigationMenu' => $navContent
];

$mylotsContent = renderTemplate('mylots.php', $mylotsVar);

$layoutContent = renderLayout($mylotsContent, $navContent, $title, $isMainPage, $userAvatar);

print($layoutContent);
?>