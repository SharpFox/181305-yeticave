<?php 
date_default_timezone_set('Europe/Moscow');

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
* Возвращает результат сборки страницы.
*
* @param string $path
* @param array $varArray
* @return string
*/
function toRenderTemplate($path, $varArray) {

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
function toPrintErrorInfo($value, $currentArray) {
    if (array_key_exists($value, $currentArray)) {
        return;
    }

    header('HTTP/1.1 404 Not Found');
    print('Ошибка 404');

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
    $specialSymbols = '/[\'|\"\||\<|\>|\[|\]|\!|\?|\$|\@|\#|\%|\^|\/|\\\|\&|\~|\*|\{|\}|\+|\:|\,|\;|\`|\=|\(|\)|\§|\°]/';

    foreach($rules as $key => $rule) {
        foreach($rule as $subRule) {           
            if (isset($_POST[$key])) {
                if ($subRule === 'required' && $_POST[$key] == '') {
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
                if ($subRule === 'specialSymbols') {
                    if (preg_match($specialSymbols, $_FILES[$key]['name'])) {
                        $errors[$key][] = 'Имя файла должно содержать символы русского или латинского алфавитов, знак "_"';
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
    $fileName = $_FILES[$attributeValue]['name'];    
    $filePath = __DIR__ . '/img/';
    $fileName =  strval(time()) . "_" . $fileName;
    $_FILES[$attributeValue]['name'] = $fileName;

    try {
        move_uploaded_file($_FILES[$attributeValue]['tmp_name'], $filePath . $fileName);
    } catch (Exception $e) {
        $result = "Не удалось загрузить файл " . $fileName;
    }

    return $result; 
}
?>