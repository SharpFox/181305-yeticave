<?php 
date_default_timezone_set('Europe/Moscow');

define("NAME_TEMPLATES_PATH", 'templates/'); 

/**
* Запускает сессию. Выкидывает исключение и,
* выводит информацию об ошибке, если сессия не стартовала.
*/
function startSession() {
    if (session_start()) {
        return;
    }

    header('HTTP/1.1 500 Internal Server Error');
    print("Ошибка 500. Внутрення ошибка сервера");

    throw "Не удалось открыть сессию. Обновите страницу.";
}

startSession();

/**
* Формирует конечную версию html-страницы.
*
* @param string $mainContent
* @param string $title
* @param boolean $isMainPage
* @param array $goodsCategory
* @param string $userAvatar
* @return string
*/
function renderLayout($mainContent, $navContent, $title, $isMainPage, $userAvatar) {
    
    $layoutVar = [ 
        'content' => $mainContent,
        'navigationMenu' => $navContent,
        'title' => $title,
        'isMainPage' => $isMainPage,
        'userMenu' => renderTemplate('user-menu.php', getUserMenuVar($userAvatar))
    ];
    
    return renderTemplate('layout.php', $layoutVar);
}

/**
* Идентифицирует тип переданного данного и выполняет преобразования
* содержимого согласно преобразованиям.
*
* @param any $incomingData
*/
function identifyTypeVarForlegalizationVarSymbols(& $incomingData) {

    if (gettype($incomingData) === 'boolean' || gettype($incomingData) === 'integer'
        || gettype($incomingData) === 'double' || gettype($incomingData) === 'string') {        
            makeSymbolsLegal($incomingData);

            return;
    }

    if (gettype($incomingData) === 'array') {
        getRoundArray($incomingData);
    }     
}

/**
* Рекурсивно обходит массив и редактирует
* символы, если это необходимо.
*
* @param array $arr 
*/
function getRoundArray(& $arr) {
    foreach ($arr as $key => $value) {
        if(is_array($value)) {
            getRoundArray($value);
        } else {
             makeSymbolsLegal($arr[$key]);
        }
    }
}

/**
* Преобразует специальные символы
* в HTML-сущности, удаляет пробелов слева справа.
*  
* @param any $incomingData
*/
function makeSymbolsLegal(& $incomingData) {
    $incomingData = trim(htmlspecialchars($incomingData));
}

/**
* Возвращает время до окончания ставки
*
* @return string
*/
function getlotTimeRemaining() {
    return gmdate("H:i", (strtotime('tomorrow midnight') - strtotime('now')));   
}

/**
* Возвращает результат сборки страницы.
*
* @param string $path
* @param array $varArray
* @return string
*/
function renderTemplate($path, $varArray) {
    $path = NAME_TEMPLATES_PATH . $path;

    if (!file_exists($path)) {
        return "";
    }    

    ob_start('ob_gzhandler');
    extract($varArray, EXTR_SKIP);
    require_once $path;
    
    return ob_get_clean(); 
}

/**
* Возвращает время последней сделанной ставки 
* в относительном формате.
*
* @param number $time
* @return string
*/
function getHumanTimeOfLastRate($time) {
    $time = time() - $time; 
    
    if ($time >= DAY_SECONDS) {
        return date('d.m.y \в H:i', $time);
    }
    
    if ($time < DAY_SECONDS && $time >= HOUR_SECONDS) {
        return  date('h', $time) . ' часов назад';
    }
    
    return date('i', $time) . ' минут назад';
}

/**
* Возвращает время до окончания возможности
* делать ставки в относительном формате.
*
* @param number $time
* @return string
*/
function getHumanTimeUntilRateEnd($time) {
    $time = $time - time(); 
    
    if ($time >= DAY_SECONDS) {
        return date('j', $time) . ' дня';
    }
    
    if ($time < DAY_SECONDS && $time >= HOUR_SECONDS) {
        return  date('G', $time) . ' часов';
    }
    
    return date('i', $time) . ' минут';
}

/**
* Выводит на экран информацию об ошибке в случае,
* если указанный ключ или индекс отсутствуют в массиве.
*
* @param mixed $value
* @param array $currentArray
*/
function printErrorInfoNotFound($value, $currentArray) {
    if (!array_key_exists($value, $currentArray)) {
        header('HTTP/1.1 404 Not Found');
        print("Ошибка 404. Страница не найдена");    
        
        exit();
    }
}

/**
* Если сессия не активна, то выдаётся
* ошибка 403 "Доступ запрещен"
*
* @param boolean $userAuth
*/
function printErrorInfoForbidden($userAuth) {   
    if (!$userAuth) {
        header("Location: login.php"); 
        
        exit();
    }
}

/**
* Выполняет проверку введённых данных
* на форму согласно переданным правилам.
* 
* @param array $rules
* @link array $errors
*/
function validateFormFields($rules, &$errors) {
    foreach($rules as $key => $rule) {
        foreach($rule as $subRule) {           
            if (isset($_POST[$key])) {
                if ($subRule === 'required' && $_POST[$key] === '') {
                    $errors[$key][] = 'Поле не может быть пустым';
                }          
                if ($subRule === 'numeric' && !filter_var($_POST[$key], FILTER_VALIDATE_FLOAT)) {
                    $errors[$key][] = 'Данные не соответствуют типу Число';
                } 
                if ($subRule === 'notNegative' && filter_var($_POST[$key], FILTER_VALIDATE_FLOAT) && $_POST[$key] < 0) {
                    $errors[$key][] = 'Значение не может быть отрицательным';
                } 
                if ($subRule === 'date' && date("d.m.Y", strtotime($_POST[$key])) !== $_POST[$key]) {
                    $errors[$key][] = 'Дата введена в неверном формате';
                } 
                if ($subRule === 'date' && strtotime($_POST[$key]) < strtotime(date("d.m.Y", time()))) {
                    $errors[$key][] = 'Введенная дата меньше текущей: ' . date("d.m.Y", time());
                }  
                if ($subRule === 'email' && $_POST[$key] === '') {
                    $errors[$key][] = 'Введите e-mail';
                }
                if ($subRule === 'email' && $_POST[$key] !== '' && !filter_var($_POST[$key], FILTER_VALIDATE_EMAIL)) {
                    $errors[$key][] = 'E-mail адрес введен не корректно';  
                }     
                if ($subRule === 'password' && $_POST[$key] === '') {
                    $errors[$key][] = 'Введите пароль';
                }            
            }  
            if (isset($_FILES[$key])) {     
                if ($subRule === 'validateFile' && isset($_FILES[$key]['name'])) {
                    $result  = call_user_func($subRule, $key);
                        
                    if ($result !== NULL) {
                        $errors[$key][] = $result;    
                    }
                }
            }
        }
    }
}

/**
* Проверяет файл.
*
* @param string $attributeValue
* @return mixed
*/
function validateFile($attributeValue) {
    $result = null;
    $maxSize = 1048576;

    if (empty($_FILES[$attributeValue]['tmp_name'])) {
        return "Файл не выбран";    
    }

    $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES[$attributeValue]['tmp_name']);

    if ($type !== 'image/jpeg') {
        $result = 'Загрузите картинку в формате jpeg';   
    }

    if ($_FILES[$attributeValue]['size'] > $maxSize) {
        $result = empty($result) ? 'Максимальный размер файла: 1 мб' : $result .= $result . '. Максимальный размер файла: 1 мб';   
    }
    
    return $result;
}

/**
* Загружает файл на сервер.
*
* @param string attributeValue
* @return null || string
*/
function loadFileToServer($attributeValue) {  
    $result = null;    
    $filePath = __DIR__ . '/img/';
    $fileInfo = pathinfo($_FILES[$attributeValue]['name']);
    $fileName = $fileInfo['filename'] . "_" . strval(time()) . "." . $fileInfo['extension'];    
    $_FILES[$attributeValue]['name'] = $fileName; 

    try {
        move_uploaded_file($_FILES[$attributeValue]['tmp_name'], $filePath . $fileName);
    } catch (Exception $e) {
        $result = "Не удалось загрузить файл " . $fileName;
    }

    return $result; 
}

/**
* Возвращает результат поиска пользователя
* по e-mail адресу.
*
* @param string $email
* @param string $users
* @return NULL || string
*/
function searchUserByEmail($email, $users) {
    $result = null;

    foreach ($users as $user) {
        if ($user['email'] == $email) {
            $result = $user;
            break;
        }
    }

    return $result;
}

/**
* Возвращает массив с данными пользователя,
* таковой авторизован.
*
* @param string $userAvatar
* @return array
*/
function getUserMenuVar($userAvatar) {
    $sessionOpen = isset($_SESSION['user']);
    
    $userVar = [
        'isAuth' => $sessionOpen,
        'userName' => $sessionOpen ? $_SESSION['user'] : '',
        'userAvatar' => $sessionOpen ? $userAvatar : ''
    ];

    return $userVar;
}

/**
* Выполняет авторизацию пользователя.
* 
* @param array $user
* @link array $errors
* @param string $nameKeyEmail
* @param string $nameKeyPassword
* @param string $nameKeyUserName
*/
function authorizeUser($users, &$errors, $nameKeyEmail, $nameKeyPassword, $nameKeyUserName) {
    
    if (!key_exists($nameKeyEmail, $_POST)) {
        $errors[$nameKeyEmail][] = 'Не найдено свойство ' . $nameKeyEmail . '. Обратитесь в тех. поддержку';
    }

    if (!key_exists($nameKeyPassword, $_POST)) {
        $errors[$nameKeyPassword][] = 'Не найдено свойство ' . $nameKeyPassword . '. Обратитесь в тех. поддержку';
        
        return;    
    }

    $user = searchUserByEmail($_POST[$nameKeyEmail], $users);

    if ($user === NULL) {
        $errors[$nameKeyEmail][] = 'Пользователь с введённым e-mail адресом не зарегистрирован';

        return;
    }

    if (!password_verify($_POST[$nameKeyPassword], $user[$nameKeyPassword])) {
        $errors['password'][] = 'Вы ввели неверный пароль';

        return;
    } 

    $_SESSION['user'] = $user[$nameKeyUserName];
    header("Location: index.php");

    return;
}

/**
* Возвращает результат запроса данных из массива.
*
* @param array $connect
* @param string $query
* @param array $data
* @return array
*/
function selectData($connect, $query, $data = []) {
    $selectedData = [];    
    $stmt = db_get_prepare_stmt($connect, $query, $data);
    
    if (!$stmt) {
        return $selectedData;
    }

    if (!mysqli_stmt_execute($stmt)) {
        return $selectedData;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $selectedData = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $selectedData;
}

/**
* Добавляет данные в ИБ.
*
* @param array $connect
* @param string $tableName
* @param array $data
* @return mixed
*/
function insertData($connect, $tableName, $data = []) {
    $result = false;
    $keysArr = [];
    $valuesArr = [];

    foreach ($data as $key => $value) {
        $keysArr[] = $key;
        $valuesArr[] = '?';
    }

    $query = "INSERT INTO $tableName (" . implode(', ', $keysArr) . " ) VALUES ( " . implode(', ', $valuesArr) . ")";

    $stmt = db_get_prepare_stmt($connect, $query, $data);

    if (!$stmt) {
        return $result;
    }

    mysqli_stmt_execute($stmt);

    $lastInsertedId = mysqli_insert_id($connect);

    return !empty($lastInsertedId) ? $lastInsertedId : $result;
}

/**
* Выполняет произволнй запрос.
*
* @param array $connect
* @param string $query
* @param array $data
* @return array
*/
function execAnyQuery($connect, $query, $data = []) {    
    $result = false;

    $stmt = db_get_prepare_stmt($connect, $query, $data);

    if (!$stmt) {
        return $result;
    } 

    return mysqli_stmt_execute($stmt);
}
?>