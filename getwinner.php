<?php
$winners = [];

$queryString = 'SELECT id 
        FROM lots 
        WHERE endTime <= NOW() 
            AND winnerId is null;';

$findLots = selectData($connectMySQL, $queryString);
identifyTypeVarForlegalizationVarSymbols($findLots);

$lots = array_shift($findLots);
if ($lots === NULL) {
    $lots = [];
}

$queryString = 'SELECT bets.cost AS betCost, 
                    users.name AS userName, 
                    users.email AS userEmail, 
                    users.id AS userId, 
                    lots.id AS lotId,
                    lots.name AS lotName
                FROM bets
                LEFT JOIN lots ON bets.lotId = lots.id
                LEFT JOIN users ON bets.userId = users.id
                WHERE bets.lotId = ?
                ORDER BY bets.cost DESC';
      
foreach ($lots as $lot) {
    $applicants = selectData($connectMySQL, $queryString, ['lotId' => $lot]);
    $winner = array_shift($applicants);
    array_push($winners, $winner); 
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
    $transport = (new Swift_SmtpTransport(SMTP_SERVER_NAME_EMAIL_SETTING, PORT_EMAIL_SETTING))
        ->setUsername(USER_NAME_EMAIL_SETTING)
        ->setPassword(PASSWORD_EMAIL_SETTING)
        ->setEncryption(ENCRYPTING_EMAIL_SETTING);

    $mailer = new Swift_Mailer($transport);
}

foreach ($winners as $winner) {
    $emailVar = [
        'userName' => $winner['userName'],
        'lotName' => $winner['lotName'],
        'myBets' => HTTP_NAME . '://' . DOMAIN_NAME . '/mylots.php',
        'lotUrl' => HTTP_NAME . '://' . DOMAIN_NAME . '/lot.php?lot-id=' . $winner['lotId']
    ];
    $emailContant = renderTemplate('email.php', $emailVar);  

    $message = (new Swift_Message('Ваша ставка победила'))
        ->setFrom(USER_NAME_EMAIL_SETTING)
        ->setTo($winner['userEmail'])
        ->setBody($emailContant, CONTENT_TYPE_EMAIL_SETTING);

    $mailer->send($message);
}
?>