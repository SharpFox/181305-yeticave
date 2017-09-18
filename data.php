<?php 
date_default_timezone_set('Europe/Moscow');

//$isAuth = (bool) rand(0, 1);
//$userName = 'Константин';
$userAvatar = 'img/user.jpg';
$isAuth = false;

$goodsCategory = ['Доски и лыжи','Крепления','Ботинки','Одежда','Инструменты','Разное'];

$lotDefaultDescription = 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег
                          мощным щелчком и четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот
                          снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
                          кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,
                          просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.';
$defaultStep = 1;
$defaultRateEndTime = '15:28';


$goodsContent = [
    [
        'name' => "2014 Rossignol District Snowboard",
        'category' => 'Доски и лыжи',
        'cost' => 10999,
        'url' => 'img/lot-1.jpg',
        'description' => $lotDefaultDescription,
        'step' => $defaultStep,
        'lotTimeRemaining' => getlotTimeRemaining()
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'cost' => 159999,
        'url' => 'img/lot-2.jpg',
        'description' => $lotDefaultDescription,
        'step' => $defaultStep,
        'lotTimeRemaining' => getlotTimeRemaining()
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'cost' => 8000,
        'url' => 'img/lot-3.jpg',
        'description' => $lotDefaultDescription,
        'step' => $defaultStep,
        'lotTimeRemaining' => getlotTimeRemaining()
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'cost' => 10999,
        'url' => 'img/lot-4.jpg',
        'description' => $lotDefaultDescription,
        'step' => $defaultStep,
        'lotTimeRemaining' => getlotTimeRemaining()
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'cost' => 7500,
        'url' => 'img/lot-5.jpg',
        'description' => $lotDefaultDescription,
        'step' => $defaultStep,
        'lotTimeRemaining' => getlotTimeRemaining()
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'cost' => 5400,
        'url' => 'img/lot-6.jpg',
        'description' => $lotDefaultDescription,
        'step' => $defaultStep,
        'lotTimeRemaining' => getlotTimeRemaining()
    ]
];

$bets = [
    [
        'name' => 'Иван',
        'price' => 11500,
        'ts' => strtotime('-' . rand(1, 50) .' minute')
    ],
    [
        'name' => 'Константин',
        'price' => 11000,
        'ts' => strtotime('-' . rand(1, 18) .' hour')
    ],
    [
        'name' => 'Евгений',
        'price' => 10500,
        'ts' => strtotime('-' . rand(25, 50) .' hour')],
    [
        'name' => 'Семён',
        'price' => 10000,
        'ts' => strtotime('last week')
    ]
];
?>