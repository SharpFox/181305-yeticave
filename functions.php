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
?>