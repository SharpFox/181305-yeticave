<?php 
/*
* Возвращает результат сборки страницы.
*
* @param sting $path
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
* Возвращает время в относительном формате.
*
* @param number $time
* @return string
*/
function convertUnixTime($time) {
    
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
function validateForm($rules) {
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        return $errors;
    }

    foreach($rules as $key => $rule) {
        foreach($rule as $subRule) {
            if ($subRule === 'required' && !isset($_POST[$key]) || $_POST[$key] == '') {
                $errors[$key][] = 'Поле не может быть пустым';
            }
            if ($subRule === 'numeric' && isset($_POST[$key]) && !filter_var($_POST[$key], FILTER_VALIDATE_FLOAT)) {
                $errors[$key][] = 'Данные не соответствуют типу Число';
            }
        }
    }
    return $errors;
}

/*
* Проверяет файл на ряд заданных параметров:
* 1. Расширение, jpeg;
* 2. Размер файла 1 мб.
*
* @param string $imageName
* @return string 
*/
function validateFile($imageName) {
    $errorText = null;
    $maxSize = 1048576;

    if (!isset($_FILES[$imageName]) || empty($_FILES[$imageName]['name'])) {
        return $errorText;    
    }

    $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES[$imageName]['tmp_name']);
    
    $errorText = $type !== 'image/jpeg' ? $errorText .= 'Загрузите картинку в формате jpeg' : $errorText .= '';
    return $_FILES[$imageName]['size'] > $maxSize ? $errorText .= 'Максимальный размер файла: 1 мб' : $errorText .= '';
}

function loadFileToServer($imageName) {    
    if (!isset($_FILES[$imageName]['name']) && empty($_FILES[$imageName]['name'])) {
        return;     
    }
    $fileName = $_FILES[$imageName]['name'];
    $filePath = __DIR__ . '/img/';
        
    if (!file_exists($filePath)) {
        move_uploaded_file($_FILES[$imageName]['tmp_name'], $filePath . $fileName);
    }  
}

function getLotData($imageName) {
    $lotData = [];

    if (!isset($_FILES[$imageName]['name']) && empty($_FILES[$imageName]['name'])) {
        return $lotData;     
    }

    $lotData = [
        [
            'name' => htmlspecialchars($_POST['lot-name']),
            'category' => $_POST['category'],
            'cost' => htmlspecialchars($_POST['lot-rate']),
            'url' => '/img/' . $_FILES[$imageName]['name']
        ],
    ];

    return $lotData;
}
?>