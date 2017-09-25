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

$queryString = 'SELECT lots.id AS lotId, lots.name AS lotName, lots.cost AS lastCost, lots.cost + lots.step AS currentCost, lots.url, lots.description, lots.endTime, lots.createdTime, lots.step, lots.quantityBets, categories.name AS category 
    FROM lots INNER JOIN categories ON lots.categoryId = categories.id
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    mysqli_query($connectMySQL, "START TRANSACTION");
    
    $lotData = [
        'createdTime' => date("Y-m-d H:i:s", strtotime($lot['createdTime'])),
        'endTime' => date("Y-m-d H:i:s", strtotime($lot['endTime'])),
        'cost' => intval($_POST['cost']),
        'userId' => isset($_SESSION['user']) ? intval($_SESSION['userId']) : intval(NULL),
        'lotId' => isset($_GET['id']) ? intval($_GET['id']) : intval(null)
    ];  

    $betId = insertData($connectMySQL, 'bets', $lotData);

    $queryString = 'UPDATE lots SET cost = ' . intval($_POST['cost']) . ' WHERE lots.id = ' . $lotId . '';    
    $isLotUpdate = execAnyQuery($connectMySQL, $queryString);

    if ($betId && $isLotUpdate) {
        mysqli_query($connectMySQL, "COMMIT");
    }
    else {
        mysqli_query($connectMySQL, "ROLLBACK");
    }
    
    header('location: lot.php?id=' . $lotId . '');;
    exit();
}

$title = $lot['lotName'];

$queryString = 'SELECT bets.cost, bets.createdTime, bets.userId, users.name AS user   
    FROM bets INNER JOIN users ON bets.userId = users.id INNER JOIN lots ON bets.lotId = lots.id
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