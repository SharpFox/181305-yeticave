<?php
require_once('functions.php');
require_once('init.php');
require_once('config.php');

checkSessionAccess();

$title = 'Добавление лота';
$ValueOfAttributeName = 'add-img';
$errors = [];
$rules = [
    'lot-name' => [
        'required'
    ],
    'category' => [
        'required'
    ],
    'message' => [
        'required',
    ],
    'lot-rate' => [
        'required',
        'numeric',
        'notNegative',
        'notNull'
    ],
    'lot-step' => [
        'required',
        'numeric',
        'notNegative',
        'notNull'
    ],
    'lot-date' => [
        'required',
        'date'
    ],
    'add-img' => [
        'validateFile'
    ]
];

if (!empty($_POST)) {
    identifyTypeVarForlegalizationVarSymbols($_POST);
}
if (!empty($_FILES)) {
    identifyTypeVarForlegalizationVarSymbols($_FILES);
}

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navVar = [
    'categories' => $categories,
    'currentCategoryId' => isset($_GET['category-id']) ? intval($_GET['category-id']) : 0   
];
$navContent = renderTemplate('nav.php', $navVar);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
    $errors['add-img'][] = 'Возникла непредвиденная ошибка. Возможно, была предпринята попытка загрузки файла
        очень большого размера';   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    validateFormFields($rules, $errors);
    
    if (isset($_POST['category']) && $_POST['category'] === 'Выберите категорию') {
        $errors['category'][] = 'Не выбрана категория';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && empty($errors)) {   
    if (isset($_FILES['add-img']['name'])) {
        $result = loadFileToServer('add-img');
        
        if ($result !== NULL) {
            $errors['add-img'][] = $result;
        }   
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && empty($errors)) {  
    $queryString = 'SELECT id 
                    FROM categories 
                    WHERE name = ?';    
    $queryParam = [
        'category' => $_POST['category']
    ];

    $findCategory = selectData($connectMySQL, $queryString, $queryParam);

    $lotData = [
        'name' => $_POST['lot-name'],
        'cost' => intval($_POST['lot-rate']),
        'url' => '/img/' . $_FILES['add-img']['name'],
        'description' => $_POST['message'],
        'endTime' => date("Y-m-d H:i:s", strtotime($_POST['lot-date'] . ' 23:59:59')),
        'step' => intval($_POST['lot-step']),       
        'quantityBets' => 0,
        'categoryId' => $findCategory[0]['id'],
        'authorId' => isset($_SESSION['userId']) ? intval($_SESSION['userId']) : 0,
        'createdTime' => date("Y-m-d H:i:s", time())
    ];  

    $lotId = insertData($connectMySQL, 'lots', $lotData);

    $queryString = 'SELECT lots.id AS lotId, 
                        lots.name AS lotName, 
                        lots.cost AS lastCost, 
                        lots.step, 
                        lots.cost + lots.step AS currentCost, 
                        lots.url, 
                        lots.description,
                        lots.endTime, 
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

    $lot = array_shift($findLot);
    if ($lot === NULL) {
        $lot = [];
    }

    $currentUserId = isset($_SESSION['userId']) ? intval($_SESSION['userId']) : 0;
    $isCurrentUserAuthor = ($lot['authorId'] === $currentUserId) ? true : false;
    
    $lotVar = [
        'lot' => $lot,
        'navigationMenu' => $navContent,
        'bets' => array(),
        'isBetMade' => false,
        'isAuth' => isset($_SESSION['userId']),
        'isCurrentUserAuthor' => $isCurrentUserAuthor,
        'errors' => $errors
    ];

    header('location: lot.php?lot-id=' . $lotId . '');

    $mainContent = renderTemplate('lot.php', $lotVar);
} else {
    $addVar = [
        'categories' => $categories,
        'navigationMenu' => $navContent,
        'errors' => $errors
    ];

    $mainContent = renderTemplate('add.php',  $addVar);
}

$layoutContent = renderLayout($mainContent, $navContent, $title);
    
print($layoutContent);
?>