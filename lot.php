<?php
header('Content-Type: text/html; charset=utf-8');
<<<<<<< HEAD
=======

require_once('functions.php');
require_once('lot_data.php');
>>>>>>> 4b0556c03052e99cf0cc50a4a6ffd940c881f01e

require_once('functions.php');
require_once('data.php');

$goodsItem = isset($_GET['id']) ? $_GET['id'] : null;
toPrintErrorInfo($goodsItem, $goodsContent);

<<<<<<< HEAD
$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);
=======
if (!array_key_exists($goodsItem, $goodsContents)) {
    header('HTTP/1.1 404 Not Found');
    print('Ошибка 404');

    die();
}
?>
>>>>>>> 4b0556c03052e99cf0cc50a4a6ffd940c881f01e

$lotVar = [ 
    'goodsContent' => $goodsContent,
    'goodsItem' => $goodsItem,
    'navigationMenu' => $navContent,
    'bets' => $bets
];

$lotContent = toRenderTemplate('lot.php', $lotVar);

$layoutVar = [ 
    'content' => $lotContent,
    'navigationMenu' => $navContent,
    'title' => $goodsContent[$goodsItem]['name'],
    'isMainPage' => false,
    'isAuth' => $isAuth,
    'userName' => $userName,
    'userAvatar' => $userAvatar
];

$layoutContent = toRenderTemplate('layout.php', $layoutVar);
    
print($layoutContent);
?>