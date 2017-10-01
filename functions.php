<?php 
require_once('config.php');
require_once('vendor/autoload.php');

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

/**
* Формирует конечную версию html-страницы.
*
* @param string $mainContent
* @param string $navContent
* @param string $title
* @param string $userAvatar
* @return string
*/
function renderLayout($mainContent, $navContent, $title) {    
    $layoutVar = [ 
        'content' => $mainContent,
        'navigationMenu' => $navContent,
        'title' => $title,
        'userMenu' => renderTemplate('user-menu.php', getUserMenuVar())
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
        $incomingData = applyFunctionToArrayElements($incomingData, 'makeSymbolsLegal');
    }     
}

/**
* Рекурсивно обходит массив и редактирует
* символы, если это необходимо.
*
* @param array $arr 
* @param string $funcName 
* @return mixed
*/
function applyFunctionToArrayElements($arr, $funcName) {
    $result = [];

    foreach ($arr as $key => $value) {
        if(is_array($value)) {
            $ret = applyFunctionToArrayElements($value, $funcName);
            
            if(count($ret)) {
                $result[$key] = $ret;   
            }         
        } else {
            $result[$key] = call_user_func($funcName, $arr[$key]);
        }
    }

    return $result;
}

/**
* Преобразует специальные символы
* в HTML-сущности, удаляет пробелов слева справа.
*  
* @param any $incomingData
*/
function makeSymbolsLegal($incomingData) {
    if (gettype($incomingData) === 'string') {
        return trim(htmlspecialchars($incomingData));
    }

    return $incomingData;
}


/**
* Возвращает факт совпадения имени ключа
* с предопределенным именем.
*
* @param array $arr 
* @param boolean $result 
* @param string $keyName 
* @param mixed $keyValue 
*/
function findKeyAndValueInArray(& $arr, & $result, $keyName, $keyValue = NULL) {
    foreach ($arr as $key => $value) {
        if(is_array($value)) {
            findKeyAndValueInArray($value, $result, $keyName, $keyValue);

            if ($result) {
                return;
            }

            continue;
        } 
        if ($result) {
            return;
        }

        if ($keyValue === NULL) {
            $result = ($key === $keyName) ? true : false;    
            continue;  
        }

        $isKeyFound = ($key === $keyName) ? true : false;
        $result = ($isKeyFound && $value === $keyValue) ? true : false;               
    }
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
    $time = time() - strtotime($time); 
    
    if ($time >= DAY_SECONDS) {
        return date('j', $time) . ' дней назад';
    }
    
    if ($time >= HOUR_SECONDS && $time < DAY_SECONDS) {
        return  date('G', $time) . ' часов назад';
    }

    if ($time >= MINUTE_SECONDS  && $time < HOUR_SECONDS) {
        return date('i', $time) . ' минут назад';
    }
    
    return 'сейчас';
}

/**
* Возвращает время до окончания возможности
* делать ставки в относительном формате.
*
* @param number $time
* @return string
*/
function getHumanTimeUntilRateEnd($time) {
    $time = strtotime($time) - time(); 
    
    if ($time >= DAY_SECONDS) {
        return date('j', $time) . ' дня';
    }
    
    if ($time >= HOUR_SECONDS && $time < DAY_SECONDS) {
        return  date('G', $time) . ' часов';
    }

    if ($time >= MINUTE_SECONDS  && $time < HOUR_SECONDS) {
        return date('i', $time) . ' минут';
    }
    
    return 'уже вот вот';
}

/**
* Выводит на экран информацию об ошибке в случае,
* если указанный ключ или индекс отсутствуют в массиве.
*/
function printErrorInfoNotFound() {
    header('HTTP/1.1 404 Not Found');
    print("Ошибка 404. Страница не найдена");    
        
    exit();
}

/*
* Проверяет существование сессии.
*/
function checkSessionAccess() {
    if (isset($_SESSION['userName'])) {
        return;    
    }

    header("Location: login.php");

    exit();
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
                if ($subRule === 'notNull' && filter_var($_POST[$key], FILTER_VALIDATE_FLOAT) && $_POST[$key] === 0) {
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
    $maxSize = FILE_MAX_SIZE;
    $imageFormatArr = ['image/jpeg', 'image/jpg', 'image/png'];

    if (empty($_FILES[$attributeValue]['tmp_name'])) {
        return "Файл не выбран";    
    }

    $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES[$attributeValue]['tmp_name']);

    if (!in_array($type, $imageFormatArr)) {
        $result = 'Загрузите картинку в формате jpeg/jpg/png';   
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
    $filePath = FILE_IMAGE_PATH;
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
* @param array $connectMySQL
* @return array
*/
function searchUserByEmail($email, $connectMySQL) {
    $queryString = 'SELECT id,
                        email,
                        name, 
                        url, 
                        passwordHash 
                    FROM users 
                    WHERE email = ?';
    $queryParam = [
        'email' => $email
    ];

    return selectData($connectMySQL, $queryString, $queryParam);
}

/**
* Возвращает массив с данными пользователя,
* таковой авторизован.
*
* @return array
*/
function getUserMenuVar() {
    $sessionOpen = isset($_SESSION['userName']);
    
    $userVar = [
        'isAuth' => $sessionOpen,
        'userName' => $sessionOpen ? $_SESSION['userName'] : '',
        'userAvatar' => $sessionOpen ? $_SESSION['avatarUrl'] : ''
    ];

    return $userVar;
}

/**
* Выполняет авторизацию пользователя.
* 
* @link array $errors
* @param array $connectMySQL
*/
function authorizeUser(&$errors, $connectMySQL) {
    $findUser = searchUserByEmail($_POST['email'], $connectMySQL);

    $user = [];
    foreach($findUser as $key => $arr) {
        foreach($arr as $key => $value) {
            $user[$key] = $value;
        }
    }

    if (empty($user)) {
        $errors['email'][] = 'Пользователь с введённым e-mail адресом не зарегистрирован';

        return;
    }

    if (!password_verify($_POST['password'], $user['passwordHash'])) {
        $errors['password'][] = 'Вы ввели неверный пароль';

        return;
    } 

    $_SESSION['userName'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['userId'] = intval($user['id']);
    $_SESSION['avatarUrl'] = $user['url'];

    header("Location: index.php");

    return;
}

/**
* Рекурсивно обходит массив и редактирует
* символы, если это необходимо.
*
* @param array $arr 
* @param string $funcName 
* @param array $connect 
* @return mixed
*/
function doRealEscapeStringToArrayElements($arr, $funcName, $connect) {
    $result = [];

    foreach ($arr as $key => $value) {
        if(is_array($value)) {
            $ret = doRealEscapeStringToArrayElements($value, $funcName, $connect);
           
            if(count($ret)) {
                $result[] = $ret;  
            }          
        } else {
            if (gettype($arr[$key]) === 'string') {
                $result[$key] = call_user_func($funcName, $connect, $arr[$key]);
                continue;
            }
            $result[$key] = $arr[$key];          
        }
    }

    return $result;
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
    if (!empty($data)) {
        $data = doRealEscapeStringToArrayElements($data, 'mysqli_real_escape_string', $connect);
    }

    $selectedData = [];    
    $stmt = db_get_prepare_stmt($connect, $query, $data);
    
    if (!$stmt) {
        return $selectedData;
    }

    if (!mysqli_stmt_execute($stmt)) {
        return $selectedData;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
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
    if (!empty($data)) {
        $data = doRealEscapeStringToArrayElements($data, 'mysqli_real_escape_string', $connect);
    }

    $result = false;
    $placeholderArr = [];

    $keysArr = array_keys($data);

    foreach ($data as $key => $value) {
        $placeholderArr[] = '?';
    }

    $query = "INSERT INTO " . $tableName . " (" . implode(', ', $keysArr) . ") VALUES 
                (" . implode(', ', $placeholderArr) . ")";

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
    if (!empty($data)) {
        $data = doRealEscapeStringToArrayElements($data, 'mysqli_real_escape_string', $connect);
    }      
      
    $result = false;

    $stmt = db_get_prepare_stmt($connect, $query, $data);

    if (!$stmt) {
        return $result;
    } 

    return mysqli_stmt_execute($stmt);
}

/**
* Возвращает результат запроса категорий лотов.
*
* @param array $connectMySQL
* @return array
*/
function getCategories($connectMySQL) {
    $queryString = 'SELECT name,
                        id,
                        class 
                    FROM categories 
                    ORDER BY id';
    return selectData($connectMySQL, $queryString);
}
?>