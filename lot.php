<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$result = selectData($connectMySQL, 'SELECT * FROM users WHERE id = ?',  [123]);

identifyTypeVarForlegalizationVarSymbols($goodsCategory);
identifyTypeVarForlegalizationVarSymbols($goodsContent);
identifyTypeVarForlegalizationVarSymbols($lotDefaultDescription);
identifyTypeVarForlegalizationVarSymbols($defaultRateEndTime);

$goodsItem = isset($_GET['id']) ? $_GET['id'] : null;
$user_bets = [];
$isBetMade = false;

if (isset($_COOKIE['bets'])) {
    $user_bets = json_decode($_COOKIE['bets'], true);

    foreach($user_bets as $key=> $value) {
        if ($user_bets[$key]['goodsItem'] === $goodsItem) { 
            $isBetMade = true;
        }
    }    
}

printErrorInfoNotFound($goodsItem, $goodsContent);

$title = $goodsContent[$goodsItem]['name'];
$isMainPage = false;
$descriptionDefaulItem = 0;

$navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentLot = [
        'goodsItem' => $goodsItem,
        'name' => $goodsContent[$goodsItem]['name'],
        'category' => $goodsContent[$goodsItem]['category'],
        'cost' => $_POST['cost'],
        'url' => $goodsContent[$goodsItem]['url'],
        'timeBetting' => time(),
        'lotTimeRemaining' => $goodsContent[$goodsItem]['lotTimeRemaining']
    ];

    array_push($user_bets, $currentLot);

    $user_bets_encoded = json_encode($user_bets);
    header('location: mylots.php');
    setcookie('bets', $user_bets_encoded, time() + DAY_SECONDS);

    exit();
}

$lotVar = [ 
    'goodsContent' => $goodsContent,
    'goodsItem' => $goodsItem,
    'navigationMenu' => $navContent,
    'bets' => $bets,
    'lotDescription' => $lotDefaultDescription,
    'descriptionDefaulItem' => $descriptionDefaulItem,
    'rateEndTime' => $defaultRateEndTime,
    'isBetMade' => $isBetMade,
    'isAuth' => isset($_SESSION['user'])
];

$lotContent = renderTemplate('lot.php', $lotVar);

$layoutContent = renderLayout($lotContent, $navContent, $title, $isMainPage, $userAvatar);
    
print($layoutContent);
?>