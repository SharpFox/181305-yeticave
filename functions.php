<?php 
function renderTemplate($path, $varArray) {

    if (!file_exists($path)) {
        return "";
    }
   
    require_once $path;

    ob_start('ob_gzhandler');
    extract($varArray, EXTR_SKIP);
    
    return ob_get_clean(); 
}
?>