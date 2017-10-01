<?php
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$title = 'Регистрация';
$errors = [];
$rules = [
    'email' => [
        'required',
        'email'
    ],
    'password' => [
        'required', 
        'password'
    ],
    'name' => [
        'required',
    ],
    'message' => [
        'required'
    ],
    'add-img' => [
        'validateFile'
    ]
];

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navVar = [
    'categories' => $categories,
    'currentCategoryId' => isset($_GET['category-id']) ? intval($_GET['category-id']) : 0   
];
$navContent = renderTemplate('nav.php', $navVar);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
    $errors['add-img'][] = 'Возникла непредвиденная ошибка. Возможно, была предпринята попытка загрузки файла
        очень большого размера';   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    validateFormFields($rules, $errors);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && empty($errors)) {   
    if (isset($_FILES['add-img']['name'])) {
        $result = loadFileToServer('add-img');
        
        if ($result !== NULL) {
            $errors['add-img'][] = $result;
        }   
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && empty($errors)) {  
    $findUser = searchUserByEmail($_POST['email'], $connectMySQL);

    if (empty($findUser)) {    
        $userData = [
            'email' => $_POST['email'],
            'passwordHash' => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'name' => $_POST['name'],
            'url' => '/img/' . $_FILES['add-img']['name'],
            'contacts' => $_POST['message'],
            'createdTime' => date("Y-m-d H:i:s", time())
        ];  

        $userId = insertData($connectMySQL, 'users', $userData);

        $queryString = 'SELECT users.id, 
                            users.email, 
                            users.passwordHash, 
                            users.name, 
                            users.url, 
                            users.contacts, 
                            users.createdTime 
                        FROM users
                        WHERE users.id = ?';           
        $queryParam = [
            'id' => $userId
        ];
        
        $findUser = selectData($connectMySQL, $queryString, $queryParam);        
        identifyTypeVarForlegalizationVarSymbols($findUser);

        $user = convertTwoIntoOneDimensionalArray($findUser);

        if (empty($user)) {
            $errors['newUser'][] = 'Не удалось зарегистрировать нового пользователя. Повторите попытку позже.';      
        }
    } else {
        $errors['email'][] = 'Пользователь с таким e-mail адресом уже зарегистрирован'; 
    }
    
    if (!empty($errors)) {
        $loginVar = [
            'errors' => $errors,
            'navigationMenu' => $navContent
        ];    
        
        $mainContent = renderTemplate('sign-up.php', $loginVar);
    }     
    else {
        header('location: login.php');
        exit;
    }
} else {
    $signUpVar = [
        'navigationMenu' => $navContent,
        'errors' => $errors
    ];

    $mainContent = renderTemplate('sign-up.php', $signUpVar);
}

$layoutContent = renderLayout($mainContent, $navContent, $title);
    
print($layoutContent);
?>