<?php
require_once('functions.php');
require_once('init.php');
require_once('data.php');

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
        'notNegative'
    ],
    'lot-step' => [
        'required',
        'numeric',
        'notNegative'
    ],
    'lot-date' => [
        'required',
        'date'
    ],
    'add-img' => [
        'validateFile',
        'specialSymbols'
    ]
];

$queryString = 'SELECT name FROM categories ORDER BY id';
$categories = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($categories);

if (!empty($_POST)) {
    identifyTypeVarForlegalizationVarSymbols($_POST);
}
if (!empty($_FILES)) {
    identifyTypeVarForlegalizationVarSymbols($_FILES);
}

$navContent = renderTemplate('nav.php', ['categories' => $categories]);

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

    $queryString = 'SELECT id FROM categories WHERE name = "' . $_POST['category'] . '"';
    $findCategory = selectData($connectMySQL, $queryString);

    $lotData = [
        'name' => $_POST['lot-name'],
        'categoryId' => $findCategory[0]['id'],
        'cost' => $_POST['lot-rate'],
        'url' => '/img/' . $_FILES['add-img']['name'],
        'description' => $_POST['message'],
        'step' => $_POST['lot-step'],
        'endTime' => date("Y-m-d H:i:s", strtotime($_POST['lot-date'] . ' 23:59:59')),
        'quantityBets' => 0,
        'authorId' => isset($_SESSION['user']) ? $_SESSION['userId'] : NULL, 
        'winnerId' => NULL,
        'createdTime' => date("Y-m-d H:i:s", time())
    ];  

    $lotId = insertData($connectMySQL, 'lots', $lotData);
    
    $queryString = 'SELECT lots.id, lots.name, lots.cost, lots.url, lots.description, lots.endTime, lots.step, lots.quantityBets, categories.name AS category 
    FROM lots LEFT JOIN categories ON lots.categoryId = categories.id
    WHERE lots.id = ' . $lotId;
        
    $findLot = selectData($connectMySQL, $queryString);
    
    identifyTypeVarForlegalizationVarSymbols($findLot);

    $lot = [];
    foreach($findLot as $key => $arr) {
        foreach($arr as $key => $value) {
            $lot[$key] = $value;
        }
    }
    
    $lotVar = [
        'lot' => $lot,
        'navigationMenu' => $navContent,
        'bets' => array(),
        'isBetMade' => false,
        'isAuth' => isset($_SESSION['user'])
    ];

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