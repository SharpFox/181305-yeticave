<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');

$title = 'Вход';
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

if (!empty($_POST)) {
    identifyTypeVarForlegalizationVarSymbols($_POST);
}

$queryString = 'SELECT name FROM categories ORDER BY id';
$categories = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($categories);

$navContent = renderTemplate('nav.php', ['categories' => $categories]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    validateFormFields($rules, $errors);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && empty($errors)) { 
    authorizeUser($errors, $connectMySQL); 
}  

$loginVar = [
    'errors' => $errors,
    'navigationMenu' => $navContent
];    

$loginContent = renderTemplate('login.php', $loginVar);
$layoutContent = renderLayout($loginContent, $navContent, $title);
    
print($layoutContent);
?>