<?php
require_once('functions.php');
require_once('data.php');

$ValueOfAttributeName = 'add-img';
$lotData = [];
$errors = [];
$errorFileLoading = NULL;
$rules = [
    'lot-name' => [
        'required',
        'validateFormFields'
    ],
    'category' => [
        'required',        
        'validateFormFields'
    ],
    'message' => [
        'required',
        'validateFormFields'
    ],
    'lot-rate' => [
        'required',
        'numeric',
        'notNegative',
        'validateFormFields'
    ],
    'lot-step' => [
        'required',
        'numeric',
        'notNegative',
        'validateFormFields'
    ],
    'lot-date' => [
        'required',
        'date',
        'validateFormFields'
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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
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
        'errorFileLoading' => $errorFileLoading
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

$layoutVar = [ 
    'content' => $content,
    'navigationMenu' => $navContent,
    'title' => 'Добавление лота',
    'isMainPage' => false,
    'isAuth' => $isAuth,
    'userName' => $userName,
    'userAvatar' => $userAvatar
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>