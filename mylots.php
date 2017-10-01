<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('config.php');

if (!isset($_SESSION['userId'])) {
    checkSessionAccess();
}

$title = 'Мои ставки';

$queryString = 'SELECT bets.createdTime, 
                    bets.cost, 
                    bets.lotId, 
                    categories.name AS category, 
                    lots.name AS lotName, 
                    lots.url AS lotsUrl,
                    lots.endTime,
                    lots.winnerId
                FROM bets 
                INNER JOIN lots ON bets.lotId = lots.id 
                INNER JOIN categories ON lots.categoryId = categories.id 
                INNER JOIN users ON bets.userId = users.id
                WHERE users.email = ?';
$queryParam = [
    'email' => $_SESSION['email']
];

$bets = selectData($connectMySQL, $queryString, $queryParam);
identifyTypeVarForlegalizationVarSymbols($bets);

$categories = getCategories($connectMySQL);
identifyTypeVarForlegalizationVarSymbols($categories);

$navVar = [
    'categories' => $categories,
    'currentCategoryId' => isset($_GET['category-id']) ? intval($_GET['category-id']) : 0   
];
$navContent = renderTemplate('nav.php', $navVar);

$mylotsVar = [ 
    'bets' => $bets,
    'navigationMenu' => $navContent
];

$mylotsContent = renderTemplate('mylots.php', $mylotsVar);

$layoutContent = renderLayout($mylotsContent, $navContent, $title);

print($layoutContent);
?>