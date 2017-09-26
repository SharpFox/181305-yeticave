<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');
require_once('data.php');

if (!isset($_SESSION['user'])) {
    printErrorInfoForbidden();
}

$title = 'Мои ставки';

$queryString = 'SELECT bets.createdTime, bets.endTime, bets.cost, bets.lotId, categories.name AS category, lots.name AS lotName, lots.url AS lotsUrl
    FROM bets 
    INNER JOIN lots ON bets.lotId = lots.id 
    INNER JOIN categories ON lots.categoryId = categories.id 
    INNER JOIN users ON bets.userId = users.id
    WHERE users.email = "' . $_SESSION['email'] .'"';

$bets = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($bets);

$queryString = 'SELECT name FROM categories ORDER BY id';
$categories = selectData($connectMySQL, $queryString);

identifyTypeVarForlegalizationVarSymbols($categories);

$navContent = renderTemplate('nav.php', ['categories' => $categories]);

$mylotsVar = [ 
    'bets' => $bets,
    'navigationMenu' => $navContent
];

$mylotsContent = renderTemplate('mylots.php', $mylotsVar);

$layoutContent = renderLayout($mylotsContent, $navContent, $title);

print($layoutContent);
?>