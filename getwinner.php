<?php
$winners = [];

$queryString = 'SELECT id 
        FROM lots 
        WHERE endTime <= NOW() 
            AND winnerId is null;';

$lots = selectData($connectMySQL, $queryString);
identifyTypeVarForlegalizationVarSymbols($lots);

$lots = convertTwoIntoOneDimensionalArray($lots);

$queryString = 'SELECT max(bets.cost) AS betCost, 
                    users.name AS userName, 
                    users.email AS userEmail, 
                    users.id AS userId, 
                    lots.id AS lotId,
                    lots.name AS lotName
                FROM bets
                INNER JOIN lots ON bets.lotId = lots.id
                INNER JOIN users ON bets.userId = users.id
                WHERE bets.lotId = ?;';
      
foreach ($lots as $lot) {
    $winner = selectData($connectMySQL, $queryString, ['lotId' => $lot]);
    array_push($winners, convertTwoIntoOneDimensionalArray($winner)); 
}

identifyTypeVarForlegalizationVarSymbols($winners);

$queryString = 'UPDATE lots 
                SET winnerId = ? 
                WHERE id = ?;';

foreach ($winners as $winner) {

    $queryParam = [
        'winnerId' => $winner['userId'], 
        'id' => $winner['lotId']
    ];                
    execAnyQuery($connectMySQL, $queryString, $queryParam);
}

if (!empty($winners)) {
    $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465))
        ->setUsername('doingsdone@mail.ru')
        ->setPassword('rds7BgcL')
        ->setEncryption('ssl');

    $mailer = new Swift_Mailer($transport);
}

foreach ($winners as $winner) {
    $emailVar = [
        'userName' => $winner['userName'],
        'lotName' => $winner['lotName'],
        'myBets' => 'http://yeti-cave/mylots.php',
        'lotUrl' => 'http://yeti-cave/lot.php?id=' . $winner['lotId']
    ];
    $emailContant = renderTemplate('email.php', $emailVar);  

    $message = (new Swift_Message('Ваша ставка победила'))
        ->setFrom('doingsdone@mail.ru')
        ->setTo($winner['userEmail'])
        ->setBody($emailContant, 'text/html');

    $mailer->send($message);
}
?>