<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

define("LOTS_NUMBER_PAGE", 9);

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$currentCategoryId = 0;

if ($currentPage <= 0) {
    printErrorInfoNotFound();
}

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navVar = [
    'categories' => $categories,
    'currentCategoryId' => $currentCategoryId   
];
$navContent = renderTemplate('nav.php', $navVar);

$searchInfo = isset($_GET['search']) ? $_GET['search'] : "";
identifyTypeVarForlegalizationVarSymbols($searchInfo);

$maskSearchInfo = "%$searchInfo%";

$queryString = 'SELECT count(lots.id) AS lotsCount 
                FROM lots
                WHERE lots.name LIKE ?
                    OR lots.description LIKE ?;';
$queryParam = [
    'name' => $maskSearchInfo,
    'description' => $maskSearchInfo
];
$lotsCount = selectData($connectMySQL, $queryString, $queryParam);
$lotsCount = convertTwoIntoOneDimensionalArray($lotsCount);

$pageCount = intval(ceil($lotsCount['lotsCount'] / LOTS_NUMBER_PAGE));
$offset = ($currentPage - 1) * LOTS_NUMBER_PAGE;
$pages = range(1, $pageCount);

$pageCount = ($pageCount === 0) ? 1 : $pageCount;

if ($currentPage > $pageCount) {
    printErrorInfoNotFound();
}

$linkParametrs = [];

$paginatorVar = [
    'pages' => $pages,
    'pageCount' => $pageCount,
    'currentPage' => $currentPage,
    'linkParametrs' => $linkParametrs,
    'scriptPHPName' => 'search'
];
$paginatorContent = renderTemplate('paginator.php', $paginatorVar);

$queryString = 'SELECT lots.id, 
                    lots.name,
                    lots.description, 
                    lots.cost, 
                    lots.url, 
                    lots.endTime, 
                    lots.createdTime, 
                    categories.name AS category
                FROM lots
                INNER JOIN categories ON lots.categoryId = categories.id
                WHERE lots.name LIKE ?
                    OR lots.description LIKE ?
                ORDER BY lots.createdTime DESC
                LIMIT ? OFFSET ?;';
$queryParam = [
    'name' => $maskSearchInfo,
    'description' => $maskSearchInfo,
    'lotsNumberPage' => LOTS_NUMBER_PAGE,
    'offset' => $offset
];
$lots = selectData($connectMySQL, $queryString, $queryParam);
identifyTypeVarForlegalizationVarSymbols($lots);

$lotsItemContent = renderTemplate('lots-item.php', ['lots' => $lots]);

$allLotsVar = [
    'categories' => $categories,
    'lotsItemContent' => $lotsItemContent,
    'paginatorContent' => $paginatorContent,
    'searchInfo' => $searchInfo,
    'navigationMenu' => $navContent        
];
$allLotsContent = renderTemplate('search.php', $allLotsVar);

$title = 'Результаты поиска по запросу «' . $searchInfo . '»';
$layoutContent = renderLayout($allLotsContent, $navContent, $title);

print($layoutContent);
?>