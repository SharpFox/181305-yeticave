<?php
require_once('functions.php');
require_once('data.php');

$ValueOfAttributeName = 'add-img';
$lotData = [];

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

$errors = validateForm($rules);

if (isset($_FILES[$ValueOfAttributeName]) || !empty($_FILES[$ValueOfAttributeName]['name'])) {
    $validateFileError = validateFile($ValueOfAttributeName);   
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors) && $validateFileError == Null) {
   
    if (isset($_FILES[$ValueOfNameAttribute]['name']) && !empty($_FILES[$ValueOfNameAttribute]['name'])) {
        $loadFileError = loadFileToServer($ValueOfAttributeName);    
    }    
    
    if (isset($_FILES[$ValueOfAttributeName]['name']) && !empty($_FILES[$ValueOfAttributeName]['name'])) {
        $lotData = [
            [
                'name' => htmlspecialchars($_POST['lot-name']),
                'category' => $_POST['category'],
                'cost' => htmlspecialchars($_POST['lot-rate']),
                'url' => '/img/' . $_FILES[$ValueOfAttributeName]['name']
            ]
        ];
    }
  
    $goodsItem = 0;    
    $descriptionDefaulItem = 0;
    
    $lotVar = [
        'goodsContent' => $lotData,
        'goodsItem' => $goodsItem,
        'descriptionDefaulItem' => $descriptionDefaulItem,
        'navigationMenu' => $navContent,
        'bets' => $bets,
        'lotDescription' => $lotDescription
    ];

    $content = toRenderTemplate('lot.php', $lotVar);
} else {
    $addVar = [
        'goodsCategory' => $goodsCategory,
        'navigationMenu' => $navContent,
        'errors' => $errors,
        'validateFileError' => $validateFileError
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