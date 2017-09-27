<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$title = 'Главная';
$currentTimeMinusOneDay = date("Y-m-d H:i:s", time() - DAY_SECONDS);

$queryString = 'SELECT lots.id, 
                    lots.name, 
                    lots.cost, 
                    lots.url, 
                    lots.endTime, 
                    lots.createdTime, 
                    categories.name AS category 
                FROM lots 
                INNER JOIN categories ON lots.categoryId = categories.id
                WHERE lots.endTime > ? 
                ORDER BY lots.createdTime DESC';
$queryParam = [
    'category' => $currentTimeMinusOneDay
];

$lots = selectData($connectMySQL, $queryString, $queryParam);
identifyTypeVarForlegalizationVarSymbols($lots);

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navContent = renderTemplate('nav.php', ['categories' => $categories]);

$lotsItemContent = renderTemplate('lots-item.php', ['lots' => $lots]); 

$indexVar = [ 
    'categories' => $categories,
    'lotsItemContent' => $lotsItemContent    
];

$indexContent = renderTemplate('index.php', $indexVar);
$layoutContent = renderLayout($indexContent, $navContent, $title);

print($layoutContent);
?>