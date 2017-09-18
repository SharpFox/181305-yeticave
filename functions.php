<?php 
date_default_timezone_set('Europe/Moscow');

/*
* Запускает сессию.
* Выкидывает исключение, если
* сессия не стартовала.
*/
function startSession() {
    if (!session_start()) {
        header('HTTP/1.1 500 Internal Server Error');
        print("Ошибка 500. Внутрення ошибка сервера");

        throw "Не удалось открыть сессию. Обновите страницу.";
    }
}

startSession();

/*
* Идентифицирует тип переданного данного и выполняет преобразования
* содержимого согласно преобразованиям.
*
* @param any $incomingData
* @return any
*/
function identifyTypeVarForlegalizationVarSymbols($incomingData){

    if (gettype($incomingData) === 'boolean' || gettype($incomingData) === 'integer'
        || gettype($incomingData) === 'double' || gettype($incomingData) === 'string') {
        
            return makeSymbolsLegal($incomingData);
    }

    if (gettype($incomingData) !== 'array') {
        return $incomingData;
    }
  
    $isMultiArray = (count($incomingData, COUNT_RECURSIVE) -  count($incomingData)) > 0 ? true : false;
    $level = getLevelNesting($incomingData);  

    //foreach($incomingData as $i => $value) {

    //}
    
    if (!$isMultiArray) {
        foreach($incomingData as $i => $value) {
            $incomingData[$i] = makeSymbolsLegal($value);    
        }     
    } else {
        foreach($incomingData as $i => $subIncomingData) {
            foreach($subIncomingData as $j => $value) {
                $incomingData[$i][$j] = makeSymbolsLegal($value);    
            }
        }       
    }

    return $incomingData;
}

/*
* Возвращает результат преобразования специальных
* символов в HTML-сущности, удаления пробелов слева справа.
*  
* @param $incomingData
* @return string
*/
function makeSymbolsLegal($incomingData) {
    $incomingData = htmlspecialchars($incomingData);
    return trim($incomingData);
}

/*
* Возвращает уровень вложенности массива.
* 
* @ param array incomingArray 
* @return number
*/
function getLevelNesting($incomingArray) {
    $level = 0;
    $v = current($incomingArray);

    while (is_array($v)) {
         $level++;
         $v = current($incomingArray);
    }

    return $level;
}

/*
* Возвращает время до окончания ставки
*
* @return string
*/
function getlotTimeRemaining() {
    $tomorrow = strtotime('tomorrow midnight');
    $now = strtotime('now');
    
    return gmdate("H:i", ($tomorrow - $now));   
}

/*
* Возвращает результат сборки страницы.
*
* @param string $path
* @param array $varArray
* @return string
*/
function toRenderTemplate($path, $varArray) {

    //$varArray = identifyTypeVarForlegalizationVarSymbols($varArray);

    $path = "templates/" . $path;

    if (!file_exists($path)) {
        return "";
    }    

    ob_start('ob_gzhandler');
    extract($varArray, EXTR_SKIP);
    require_once $path;
    
     return ob_get_clean(); 
}

/*
* Возвращает время последней сделанной ставки 
* в относительном формате.
*
* @param number $time
* @return string
*/
function getHumanTimeOfLastRate($time) {
    
    $oneDay = 86400;
    $oneHour = 3600;
    
    $time = time() - $time; 
    
    if ($time >= $oneDay) {
        return date('d.m.y \в H:i', $time);
    }
    
    if ($time < $oneDay && $time >= $oneHour) {
        return  date('h', $time) . ' часов назад';
    }
    
    return date('i', $time) . ' минут назад';
}

/*
* Возвращает время до окончания возможности
* делать ставки в относительном формате.
*
* @param number $time
* @return string
*/
function getHumanTimeUntilRateEnd($time) {
    
    $oneDay = 86400;
    $oneHour = 3600;
    
    $time = $time - time(); 
    
    if ($time >= $oneDay) {
        return date('j', $time) . ' дня';
    }
    
    if ($time < $oneDay && $time >= $oneHour) {
        return  date('G', $time) . ' часов';
    }
    
    return date('i', $time) . ' минут';
}

/*
* Выводит на экран информацию об ошибке в случае,
* если указанный ключ или индекс отсутствуют в массиве.
*
* @param mixed $value
* @param array $currentArray
*/
function printErrorInfoNotFound($value, $currentArray) {
    if (array_key_exists($value, $currentArray)) {
        return;
    }

    header('HTTP/1.1 404 Not Found');
    print("Ошибка 404. Страница не найдена");

    die();
}

/*
* Если сессия не активна, то выдаётся
* ошибка 403 "Доступ запрещен"
*
* @param string $userAuth
*/
function printErrorInfoForbidden($userAuth) {   
    if ($userAuth) {
        return;
    }

    //$accessDenied = "Ошибка 403. Доступ запрещен";
    header("Location: login.php");    


    die();
}

/*
* Выполняет проверку введённых данных
* на форму согласно переданным правилам.
* 
* @param array $rules
* @return array
*/
function validateFormFields($rules) {
    $errors = [];
    $specialSymbolEmail = '/[\@]/';

    foreach($rules as $key => $rule) {
        foreach($rule as $subRule) {           
            if (isset($_POST[$key])) {
                if ($subRule === 'required' && $_POST[$key] === '') {
                    $errors[$key][] = 'Поле не может быть пустым';
                }          
                if ($subRule === 'numeric' && !filter_var($_POST[$key], FILTER_VALIDATE_FLOAT)) {
                    $errors[$key][] = 'Данные не соответствуют типу Число';
                } 
                if ($subRule === 'notNegative' && filter_var($_POST[$key], FILTER_VALIDATE_FLOAT)) {
                    if ($_POST[$key] < 0) {
                        $errors[$key][] = 'Значение не может быть отрицательным';
                    }
                } 
                if ($subRule === 'date') {
                    if (date("d.m.Y", strtotime($_POST[$key])) !== $_POST[$key]) {
                        $errors[$key][] = 'Дата введена в неверном формате';
                    } 
                    if (strtotime($_POST[$key]) < strtotime(date("d.m.Y", time()))) {
                        $errors[$key][] = 'Введенная дата меньше текущей: ' . date("d.m.Y", time());
                    } 
                }  
                if ($subRule === 'email') {
                    if ($_POST[$key] === '') {
                        $errors[$key][] = 'Введите e-mail';
                    } else {
                        if (!preg_match($specialSymbolEmail, $_POST[$key])) {
                            $errors[$key][] = 'E-mail адрес введен не корректно';  
                        } 
                    }    
                }     
                if ($subRule === 'password') {
                    if ($_POST[$key] === '') {
                        $errors[$key][] = 'Введите пароль';
                    } 
                }            
            }  
            if (isset($_FILES[$key])) {     
                if ($subRule === 'validateFile') {
                    if (isset($_FILES[$key]['name']) || !empty($_FILES[$key]['name'])) {
                        $result = call_user_func($subRule, $key);
                        if ($result !== NULL) {
                            $errors[$key][] = $result;    
                        } 
                    }
                }
            }
        }
    }
    return $errors;
}

/*
* Проверяет файл на ряд заданных параметров:
*
* @param string $attributeValue
* @return null || string
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

/*
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

/*
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

/*
* Возвращает массив с данными пользователя,
* таковой авторизован.
*
* @param string $userAvatar
* @return array
*/
function getUserMenuVar($userAvatar) {
    $userVar = NULL;
    $sessionOpen = isset($_SESSION['user']);
    
    $userVar = [
        'isAuth' => $sessionOpen,
        'userName' => $sessionOpen ? $_SESSION['user'] : '',
        'userAvatar' => $sessionOpen ? $userAvatar : ''
    ];

    return $userVar;
}
?>