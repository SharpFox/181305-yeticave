<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');
require_once('getwinner.php');

define("LOTS_NUMBER_PAGE", 3);

$title = 'Главная';
$currentTimeMinusOneDay = date("Y-m-d H:i:s", time() - DAY_SECONDS);
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$currentCategoryId = isset($_GET['category-id']) ? intval($_GET['category-id']) : 0;

$queryCondition = "";
$queryParam = [];

if ($currentCategoryId !== 0) { 
    $queryCondition = 'WHERE categoryId = ?';   

    $queryParam = [
        'categoryId' => $currentCategoryId
    ];
}

$queryString = 'SELECT count(id) AS count
                FROM lots 
                ' . $queryCondition . ';';

$lotsCount = selectData($connectMySQL, $queryString, $queryParam);
$lotsCount = convertTwoIntoOneDimensionalArray($lotsCount);

$pageCount = intval(ceil($lotsCount['count'] / LOTS_NUMBER_PAGE));
$offset = ($currentPage - 1) * LOTS_NUMBER_PAGE;
$pages = range(1, $pageCount);

$pageCount = ($pageCount === 0) ? 1 : $pageCount;

if ($currentPage > $pageCount || $currentPage <= 0) {
    printErrorInfoNotFound();
}

$queryCondition = "";
$queryParam = [
    'lotsNumberPage' => LOTS_NUMBER_PAGE,
    'offset' => $offset
];

if ($currentCategoryId !== 0) { 
    $queryCondition = 'WHERE categoryId = ?'; 
    $queryParam = array('categoryId' => $currentCategoryId) + $queryParam;
}

$queryString = 'SELECT lots.id, 
                    lots.name, 
                    lots.cost, 
                    lots.url, 
                    lots.endTime, 
                    lots.createdTime, 
                    lots.categoryId,
                    categories.name AS category
                FROM lots
                INNER JOIN categories ON lots.categoryId = categories.id 
                ' . $queryCondition . '
                ORDER BY lots.createdTime DESC
                LIMIT ? OFFSET ?;';

$lots = selectData($connectMySQL, $queryString, $queryParam);
identifyTypeVarForlegalizationVarSymbols($lots);

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navVar = [
    'categories' => $categories,
    'currentCategoryId' => $currentCategoryId   
];
$navContent = renderTemplate('nav.php', $navVar);

$lotsItemContent = renderTemplate('lots-item.php', ['lots' => $lots]);

$paginatorVar = [
    'pages' => $pages,
    'pageCount' => $pageCount,
    'currentPage' => $currentPage,
    'scriptPHPName' => 'index'
];
$paginatorContent = renderTemplate('paginator.php', $paginatorVar);

$indexVar = [ 
    'categories' => $categories,
    'lotsItemContent' => $lotsItemContent,
    'paginatorContent' => $paginatorContent,
    'currentCategoryId' => $currentCategoryId
];
$indexContent = renderTemplate('index.php', $indexVar);

$layoutContent = renderLayout($indexContent, $navContent, $title);

print($layoutContent);
?>