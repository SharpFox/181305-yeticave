<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$queryString = 'SELECT name FROM categories ORDER BY id';
$categories = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($categories);

$lotId = isset($_GET['id']) ? $_GET['id'] : null;

$queryString = 'SELECT lots.id, lots.name, lots.cost, lots.url, lots.description, lots.endTime, lots.step, lots.quantityBets, categories.name AS category 
    FROM lots LEFT JOIN categories ON lots.categoryId = categories.id
    WHERE lots.id = ' . $lotId;

$findLot = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($findLot);

$isLotFind = false;
!findIdInLot($findLot, $isLotFind);
if (!$isLotFind) {
    printErrorInfoNotFound();
}

$lot = [];
foreach($findLot as $key => $arr) {
    foreach($arr as $key => $value) {
        $lot[$key] = $value;
    }
}

$title = $lot['name'];

$queryString = 'SELECT bets.cost, bets.createdTime, bets.userId, users.name AS user   
    FROM bets JOIN users ON bets.userId = users.id JOIN lots ON bets.lotId = lots.id
    WHERE bets.lotId = ' . $lotId;

$bets = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($bets);

$isBetMade = false;

foreach($bets as $key=> $bet) {
    if ($bet['userId'] === (isset($_SESSION['userId']) ? $_SESSION['userId'] : NULL)) { 
        $isBetMade = true;
        break;
    }
}    

$navContent = renderTemplate('nav.php', ['categories' => $categories]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('location: mylots.php');
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

$layoutContent = renderLayout($lotContent, $navContent, $title);
    
print($layoutContent);
?>