<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$queryString = 'SELECT name FROM categories ORDER BY id';
$categories = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($categories);

$lotItem = isset($_GET['id']) ? $_GET['id'] : null;

$queryString = 'SELECT lots.id, lots.name, lots.cost, lots.url, lots.description, lots.endTime, lots.step, categories.name AS category 
    FROM lots LEFT JOIN categories ON lots.categoryId = categories.id
    WHERE lots.id = ' . $lotItem;

$lot = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($lot);

if (!getRoundArray($lot, 'findIdInLot')) {
    printErrorInfoNotFound();
}

$queryString = 'SELECT bets.cost, bets.createdTime, users.name AS user   
    FROM bets JOIN users ON bets.userId = users.id JOIN lots ON bets.lotId = lots.id
    WHERE bets.lotId = ' . $lotItem;

$bets = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($bets);

$user_bets = [];
$isBetMade = false;

if (isset($_COOKIE['bets'])) {
    $user_bets = json_decode($_COOKIE['bets'], true);

    foreach($user_bets as $key=> $value) {
        if ($user_bets[$key]['lotItem'] === $lotItem) { 
            $isBetMade = true;
        }
    }    
}

$title = $lot[$lotItem]['name'];
$isMainPage = false;
$descriptionDefaulItem = 0;

$navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentLot = [
        'lotItem' => $lotItem,
        'name' => $lot['name'],
        'category' => $lot['category'],
        'cost' => $_POST['cost'],
        'url' => $lot['url'],
        'timeBetting' => time(),
        'lotTimeRemaining' => $lot['lotTimeRemaining']
    ];

    array_push($user_bets, $currentLot);

    $user_bets_encoded = json_encode($user_bets);
    header('location: mylots.php');
    setcookie('bets', $user_bets_encoded, time() + DAY_SECONDS);

    exit();
}

$lotVar = [ 
    'lot' => $lot,
    'navigationMenu' => $navContent,
    'bets' => $bets,
    'isBetMade' => $isBetMade,
    'isAuth' => isset($_SESSION['user'])
];

$lotContent = renderTemplate('lot.php', $lotVar);

$layoutContent = renderLayout($lotContent, $navContent, $title, $userAvatar);
    
print($layoutContent);
?>