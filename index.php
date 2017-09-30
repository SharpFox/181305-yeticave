<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

$title = 'Главная';
$currentTimeMinusOneDay = date("Y-m-d H:i:s", time() - DAY_SECONDS);
$lotsNumberPage = 3;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

$queryString = 'SELECT count(lots.id) AS count 
                    FROM lots;';

$lotsCount = selectData($connectMySQL, $queryString);

$lotsCount = convertTwoIntoOneDimensionalArray($lotsCount);

$pageCount = intval(ceil($lotsCount['count'] / $lotsNumberPage));
$offset = ($currentPage - 1) * $lotsNumberPage;
$pages = range(1, $pageCount);

$queryString =  'SELECT lots.id, 
                    lots.name, 
                    lots.cost, 
                    lots.url, 
                    lots.endTime, 
                    lots.createdTime, 
                    categories.name AS category
                FROM lots
                INNER JOIN categories ON lots.categoryId = categories.id
                ORDER BY lots.createdTime DESC
                LIMIT ? OFFSET ?;';
$queryParam = [
    'lotsNumberPage' => $lotsNumberPage,
    'offset' => $offset
];

$lots = selectData($connectMySQL, $queryString, $queryParam);
identifyTypeVarForlegalizationVarSymbols($lots);

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navContent = renderTemplate('nav.php', ['categories' => $categories]);

$lotsItemContent = renderTemplate('lots-item.php', ['lots' => $lots]);

$paginatorVar = [
    'pages' => $pages,
    'pageCount' => $pageCount,
    'currentPage' => $currentPage
];
$paginatorContent = renderTemplate('paginator.php', $paginatorVar);

$indexVar = [ 
    'categories' => $categories,
    'lotsItemContent' => $lotsItemContent,
    'paginatorContent' => $paginatorContent    
];
$indexContent = renderTemplate('index.php', $indexVar);

$layoutContent = renderLayout($indexContent, $navContent, $title);

print($layoutContent);
?>