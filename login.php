<?php
require_once('functions.php');
require_once('data.php');
require_once('userdata.php');

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

$navVar = ['goodsCategory' => $goodsCategory];
$navContent = toRenderTemplate('nav.php', $navVar);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    foreach ($_POST as $postKey => $value) {
        $_POST[$postKey] = makeSymbolsLegal($_POST[$postKey]);  
    }

    $errors = validateFormFields($rules);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && empty($errors)) {    
    session_start();
    
    $user = searchUserByEmail($_POST['email'], $users);

    if ($user !== NULL) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user['name'];
            header("Location: index.php");
        } else {
            $errors['password'][] = 'Вы ввели неверный пароль';
        }
    } 
    else {
        $errors['email'][] = 'Пользователь с введённым e-mail адресом не зарегистрирован';     
    }
    $loginVar = [
        'errors' => $errors,
        'navigationMenu' => $navContent
    ];    

    $content = toRenderTemplate('login.php', $loginVar);   
} else {
    $loginVar = [
        'errors' => $errors,
        'navigationMenu' => $navContent
    ];     

    $content = toRenderTemplate('login.php', $loginVar);
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