<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');
require_once('userdata.php');

$title = 'Вход';
$isMainPage = false;

$nameKeyEmail  = 'email';
$nameKeyPassword = 'password';
$nameKeyUserName = 'name';

$errors = [];
$rules = [
    'email' => [
        'required',
        'email'
    ],
    'password' => [
        'required', 
        'password'
    ]
];

identifyTypeVarForlegalizationVarSymbols($goodsCategory);
identifyTypeVarForlegalizationVarSymbols($goodsContent);

if (!empty($_POST)) {
    identifyTypeVarForlegalizationVarSymbols($_POST);
}

$navContent = renderTemplate('nav.php', ['goodsCategory' => $goodsCategory]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    validateFormFields($rules, $errors);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST[$nameKeyEmail])) {
    $user = searchUserByEmail($_POST[$nameKeyEmail], $users);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && empty($errors)) { 
    authorizeUser($users, $errors, $nameKeyEmail, $nameKeyPassword, $nameKeyUserName); 
}  

$loginVar = [
    'errors' => $errors,
    'navigationMenu' => $navContent
];    

$loginContent = renderTemplate('login.php', $loginVar);
$layoutContent = renderLayout($loginContent, $navContent, $title, $userAvatar);
    
print($layoutContent);
?>