<?php
require_once('functions.php');
require_once('data.php');

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

$errors = validateForm($rules);
$fileErrorText = validateFile($lotImageName);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors) && $fileErrorText == Null) {
   
    loadFileToServer($lotImageName);
    
    $lotData = getLotData($lotImageName); 
    $goodsItem = 0;    
    
    $lotVar = [
        'goodsContent' => $lotData,
        'goodsItem' => $goodsItem,
        'navigationMenu' => $navContent,
        'bets' => $bets
    ];

    $content = toRenderTemplate('lot.php', $lotVar);
} else {
    $addVar = [
        'goodsCategory' => $goodsCategory,
        'navigationMenu' => $navContent,
        'errors' => $errors,
        'fileErrorText' => $fileErrorText
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