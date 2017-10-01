<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('config.php');

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 0;
$currentCategoryId = isset($_GET['category-id']) ? intval($_GET['category-id']) : 0;
$categoryName = '';

if ($currentPage <= 0) {
    printErrorInfoNotFound();
}

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$isCategoryFind = false;
findKeyAndValueInArray($categories, $isCategoryFind, 'id', $currentCategoryId);

if (!$isCategoryFind) {
    printErrorInfoNotFound();
}

$queryString = 'SELECT count(lots.id) AS count 
                FROM lots;';

$lotsCount = selectData($connectMySQL, $queryString);

foreach ($categories as $category) {
    if ($category['id'] === $currentCategoryId) {
        $categoryName = $category['name'];
        
        break;
    }
}

$navVar = [
    'categories' => $categories,
    'currentCategoryId' => $currentCategoryId   
];
$navContent = renderTemplate('nav.php', $navVar);

$queryString = 'SELECT count(id) AS count 
                FROM lots
                WHERE categoryId = ?';
$queryParam = [
    'categoryId' => $currentCategoryId
];
$findLotsCount = selectData($connectMySQL, $queryString, $queryParam);

$lotsCount = array_shift($findLotsCount);
if ($lotsCount === NULL) {
    $lotsCount = [];
}

$pageCount = intval(ceil($lotsCount['count'] / ALL_LOTS_NUMBER_LOTS_PAGE));
$offset = ($currentPage - 1) * ALL_LOTS_NUMBER_LOTS_PAGE;
$pages = range(1, $pageCount);

$pageCount = ($pageCount === 0) ? 1 : $pageCount;

if ($currentPage > $pageCount) {
    printErrorInfoNotFound();
}

$categoryParam = "&category-id=" . $currentCategoryId;
$linkParametrs = [];
array_push($linkParametrs, $categoryParam);

$paginatorVar = [
    'pages' => $pages,
    'pageCount' => $pageCount,
    'currentPage' => $currentPage,
    'currentCategoryId' => $currentCategoryId,
    'linkParametrs' => $linkParametrs,
    'scriptPHPName' => 'all-lots'
];
$paginatorContent = renderTemplate('paginator.php', $paginatorVar);

$queryString = 'SELECT lots.id, 
                    lots.name, 
                    lots.cost, 
                    lots.url, 
                    lots.endTime, 
                    lots.createdTime, 
                    categories.name AS category
                FROM lots
                INNER JOIN categories ON lots.categoryId = categories.id
                WHERE lots.categoryId = ?
                ORDER BY lots.createdTime DESC
                LIMIT ? OFFSET ?;';
$queryParam = [
    'categoryId' => $currentCategoryId, 
    'lotsNumberPage' => ALL_LOTS_NUMBER_LOTS_PAGE,
    'offset' => $offset
];
$lots = selectData($connectMySQL, $queryString, $queryParam);
identifyTypeVarForlegalizationVarSymbols($lots);

$lotsItemContent = renderTemplate('lots-item.php', ['lots' => $lots]);

$allLotsVar = [
    'categories' => $categories,
    'lotsItemContent' => $lotsItemContent,
    'paginatorContent' => $paginatorContent,
    'categoryName' => $categoryName,
    'navigationMenu' => $navContent        
];
$allLotsContent = renderTemplate('all-lots.php', $allLotsVar);

$title = 'Все лоты в категории «' . $categoryName . '»';
$layoutContent = renderLayout($allLotsContent, $navContent, $title);

print($layoutContent);
?>