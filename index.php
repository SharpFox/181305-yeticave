<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$title = 'Главная';

$queryString = 'SELECT name FROM categories ORDER BY id';
$categories = selectData($connectMySQL, $queryString);

$queryString = 'SELECT lots.id, lots.name, lots.cost, lots.url, lots.endTime, categories.name AS category 
    FROM lots INNER JOIN categories ON lots.categoryId = categories.id
    WHERE lots.endTime > "' . date('Y-m-d H:i:s', strtotime('now')) . '" ORDER BY lots.id ASC LIMIT 6';

$lots = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($categories);
identifyTypeVarForlegalizationVarSymbols($lots);

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