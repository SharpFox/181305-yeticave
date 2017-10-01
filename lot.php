<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('config.php');

$errors = [];

$rules = [
    'cost' => [
        'required',
        'numeric',
        'notNegative'
    ]
];

$lotId = isset($_GET['lot-id']) ? intval($_GET['lot-id']) : 0;

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
                    lots.authorId,
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
findKeyAndValueInArray($findLot, $isLotFind, 'lotId');

if (!$isLotFind) {
    printErrorInfoNotFound();
}

$lot = array_shift($findLot);
if ($lot === NULL) {
    $lot = [];
}

$currentUserId = isset($_SESSION['userId']) ? intval($_SESSION['userId']) : 0;
$isCurrentUserAuthor = ($lot['authorId'] === $currentUserId) ? true : false;

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
        'cost' => intval($_POST['cost']),
        'userId' => isset($_SESSION['userId']) ? intval($_SESSION['userId']) : 0,
        'lotId' => isset($_GET['lot-id']) ? intval($_GET['lot-id']) : 0
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
    
    header('location: lot.php?lot-id=' . $lotId . '');;
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
    if (intval($bet['userId']) === $currentUserId) { 
        $isBetMade = true;
        break;
    }
}    

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navVar = [
    'categories' => $categories,
    'currentCategoryId' => isset($_GET['category-id']) ? intval($_GET['category-id']) : 0   
];
$navContent = renderTemplate('nav.php', $navVar);

$lotVar = [ 
    'lot' => $lot,
    'navigationMenu' => $navContent,
    'bets' => $bets,
    'isBetMade' => $isBetMade,
    'isAuth' => isset($_SESSION['userId']),
    'isCurrentUserAuthor' => $isCurrentUserAuthor,
    'errors' => $errors
];

$lotContent = renderTemplate('lot.php', $lotVar);

$layoutContent = renderLayout($lotContent, $navContent, $title);
    
print($layoutContent);
?>