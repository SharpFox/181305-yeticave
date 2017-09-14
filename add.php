<?php
require_once('functions.php');
require_once('data.php');

$ValueOfAttributeName = 'add-img';
$lotData = [];
$errors = [];
$errorFileLoading = NULL;
$checkingAttribute = [
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
        'validateFormFields'
    ],
    'lot-step' => [
        'required',
        'numeric',
        'validateFormFields'
    ],
    'lot-date' => [
        'required',
        'validateFormFields'
    ],
    'add-img' => [
        'validateFile'
    ]
];

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateFormFields($rules);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {   
    if (isset($_FILES['add-img']['name']) && !empty($_FILES['add-img']['name'])) {
        $errors['add-img'][] = loadFileToServer('add-img');
    }

    $lotData = [
        [
            'name' => htmlspecialchars($_POST['lot-name']),
            'category' => $_POST['category'],
            'cost' => htmlspecialchars($_POST['lot-rate']),
            'url' => '/img/' . $_FILES['add-img']['name'],
            'description' => $_POST['message']
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lotData = [
            [
                'name' => htmlspecialchars($_POST['lot-name']),
                'category' => $_POST['category'],
                'cost' => htmlspecialchars($_POST['lot-rate']),
                'url' => '/img/' . $_FILES['add-img']['name'],
                'description' => $_POST['message']
            ]
            ];  
        }
    
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