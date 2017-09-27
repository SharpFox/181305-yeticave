<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$errors = [];

$rules = [
    'cost' => [
        'required',
        'numeric',
        'notNegative'
    ]
];

$lotId = isset($_GET['id']) ? intval($_GET['id']) : null;

$queryString = 'SELECT lots.id AS lotId, 
                    lots.name AS lotName, 
                    lots.cost AS lastCost, 
                    lots.cost + lots.step AS currentCost,
                    lots.url, 
                    lots.description, 
                    lots.endTime, 
                    lots.createdTime, 
                    lots.step, 
                    lots.quantityBets, 
                    categories.name AS category 
                FROM lots 
                INNER JOIN categories ON lots.categoryId = categories.id
                WHERE lots.id = ?';
$queryParam = [
    'id' => $lotId
];

$findLot = selectData($connectMySQL, $queryString, $queryParam);
identifyTypeVarForlegalizationVarSymbols($findLot);

$isLotFind = false;
findIdInLot($findLot, $isLotFind);

if (!$isLotFind) {
    printErrorInfoNotFound();
}

$lot = convertTwoIntoOneDimensionalArray($findLot);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateFormFields($rules, $errors);

    if (intval($_POST['cost']) <= $lot['lastCost']) { 
        $errors['cost'][] = 'Новая ставка не может быть меньше или равна текущей цене лота';     
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {

    mysqli_query($connectMySQL, "START TRANSACTION");
    
    $betData = [
        'createdTime' => date("Y-m-d H:i:s", time()),
        'endTime' => date("Y-m-d H:i:s", strtotime($lot['endTime'])),
        'cost' => intval($_POST['cost']),
        'userId' => isset($_SESSION['user']) ? intval($_SESSION['userId']) : intval(NULL),
        'lotId' => isset($_GET['id']) ? intval($_GET['id']) : intval(null)
    ];  

    $betId = insertData($connectMySQL, 'bets', $betData);

    $queryString = 'UPDATE lots 
                    SET cost = ?, quantityBets = ? 
                    WHERE lots.id = ?';  
    $queryParam = [
        'cost' => intval($_POST['cost']),
        'quantityBets' => $lot['quantityBets'] + 1,
        'id' => $lotId
    ];

    $isLotUpdate = execAnyQuery($connectMySQL, $queryString, $queryParam);

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

$queryString = 'SELECT bets.cost, 
                    bets.createdTime, 
                    bets.userId, 
                    users.name AS user   
                FROM bets 
                INNER JOIN users ON bets.userId = users.id 
                INNER JOIN lots ON bets.lotId = lots.id
                WHERE bets.lotId = ?';
$queryParam = [
    'lotId' => $lotId
];

$bets = selectData($connectMySQL, $queryString, $queryParam);
identifyTypeVarForlegalizationVarSymbols($bets);

$isBetMade = false;

foreach($bets as $key=> $bet) {
    if (intval($bet['userId']) === (isset($_SESSION['userId']) ? intval($_SESSION['userId']) : NULL)) { 
        $isBetMade = true;
        break;
    }
}    

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navContent = renderTemplate('nav.php', ['categories' => $categories]);

$lotVar = [ 
    'lot' => $lot,
    'navigationMenu' => $navContent,
    'bets' => $bets,
    'isBetMade' => $isBetMade,
    'isAuth' => isset($_SESSION['userId']) ? true : false,
    'errors' => $errors
];

$lotContent = renderTemplate('lot.php', $lotVar);

$layoutContent = renderLayout($lotContent, $navContent, $title);
    
print($layoutContent);
?>