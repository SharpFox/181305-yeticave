<?php 
function renderTemplate($path, $varArray) {

    if (!file_exists($path)) {
        return "";
    }    

    ob_start('ob_gzhandler');
    extract($varArray, EXTR_SKIP);
    require_once $path;
    
    return ob_get_clean(); 
}
?>