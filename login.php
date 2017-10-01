<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('config.php');

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

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navVar = [
    'categories' => $categories,
    'currentCategoryId' => isset($_GET['category-id']) ? intval($_GET['category-id']) : 0   
];
$navContent = renderTemplate('nav.php', $navVar);

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