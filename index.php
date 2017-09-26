<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$title = 'Главная';

$queryString = 'SELECT lots.id, lots.name, lots.cost, lots.url, lots.endTime, lots.createdTime, categories.name AS category 
    FROM lots INNER JOIN categories ON lots.categoryId = categories.id
    WHERE lots.endTime > (NOW() - INTERVAL 1 DAY) ORDER BY lots.createdTime DESC';

$lots = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($lots);

$queryString = 'SELECT name FROM categories ORDER BY id';
$categories = selectData($connectMySQL, $queryString);

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