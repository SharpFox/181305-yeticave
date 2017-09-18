<?php
session_start();

require_once('functions.php');
require_once('data.php');

printErrorInfoForbidden(isset($_SESSION['user']));

$ValueOfAttributeName = 'add-img';
$lotData = [];
$errors = [];
$errorFileLoading = NULL;
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

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
    $errors['add-img'][] = 'Возникла непредвиденная ошибка. Возможно, была предпринята попытка загрузки файла
        очень большого размера';   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    foreach ($_POST as $postKey => $value) {
        $_POST[$postKey] = makeSymbolsLegal($_POST[$postKey]);  
    }

    $errors = validateFormFields($rules);
    
    if ($_POST['category'] === 'Выберите категорию') {
        $errors['category'][] = 'Не выбрана категория';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && empty($errors)) {   
    if (isset($_FILES['add-img']['name']) && !empty($_FILES['add-img']['name'])) {
        $result = loadFileToServer('add-img');
        if ($result !== NULL) {
            $errors['add-img'][] = $result;
        }        
    }

    $lotData = [
        [
            'name' => htmlspecialchars($_POST['lot-name']),
            'category' => $_POST['category'],
            'cost' => htmlspecialchars($_POST['lot-rate']),
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
        'errorFileLoading' => $errorFileLoading,
        'isAuth' => isset($_SESSION['user'])
    ];

    $content = toRenderTemplate('lot.php', $lotVar);
} else {
    $addVar = [
        'goodsCategory' => $goodsCategory,
        'navigationMenu' => $navContent,
        'errors' => $errors
    ];

    $content = toRenderTemplate('add.php',  $addVar);
}

$userVar = [
    'isAuth' => isset($_SESSION['user']),
    'userName' => $_SESSION['user'],
    'userAvatar' => $userAvatar
];

$userContent = toRenderTemplate('user-menu.php', $userVar);

$layoutVar = [ 
    'content' => $content,
    'navigationMenu' => $navContent,
    'title' => 'Добавление лота',
    'isMainPage' => false,
    'userMenu' => $userContent
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>