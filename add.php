<?php
require_once('functions.php');
require_once('data.php');

printErrorInfoForbidden(isset($_SESSION['user']));

$title = 'Добавление лота';
$isMainPage = false;
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

identifyTypeVarForlegalizationVarSymbols($goodsCategory);
identifyTypeVarForlegalizationVarSymbols($goodsContent);

if (!empty($_POST)) {
    identifyTypeVarForlegalizationVarSymbols($_POST);
}
if (!empty($_FILES)) {
    identifyTypeVarForlegalizationVarSymbols($_FILES);
}

$navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);

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

    $lotData = [
        [
            'name' => $_POST['lot-name'],
            'category' => $_POST['category'],
            'cost' => $_POST['lot-rate'],
            'url' => '/img/' . $_FILES['add-img']['name'],
            'description' => $_POST['message'],
            'step' => $_POST['lot-step'],
            'lotTimeRemaining' => getHumanTimeUntilRateEnd(strtotime($_POST['lot-date'] . ' 23:59:59'))
        ]
    ];  
    
    $lotVar = [
        'goodsContent' => $lotData,
        'goodsItem' => 0,
        'descriptionDefaulItem' => 0,
        'navigationMenu' => $navContent,
        'bets' => $bets,
        'isAuth' => isset($_SESSION['user'])
    ];

    $mainContent = renderTemplate('lot.php', $lotVar);
} else {
    $addVar = [
        'goodsCategory' => $goodsCategory,
        'navigationMenu' => $navContent,
        'errors' => $errors
    ];

    $mainContent = renderTemplate('add.php',  $addVar);
}

$layoutContent = renderLayout($mainContent, $navContent, $title, $isMainPage, $userAvatar);
    
print($layoutContent);
?>