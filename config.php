<?php 
date_default_timezone_set('Europe/Moscow');

/* Время в секундах */
define("DAY_SECONDS", 86400);
define("HOUR_SECONDS", 3600);
define("MINUTE_SECONDS", 60);

/* Имя папки с шаблонами */
define("NAME_TEMPLATES_PATH", 'templates/'); 

/* Путь и размер загружаемых файлов */
define("FILE_IMAGE_PATH", __DIR__ . '/img/');
define("FILE_MAX_SIZE", 1048576);

/* Подключение к СУБД */
define("IP_MYSQL", 'localhost');
define("USERNAME_MYSQL", 'root');
define("PASSWORD_MYSQL", '');
define("DATABASENAME_MYSQL", 'yeticave_181305');
define("PORT_MYSQL", NULL);

/* Количество лотов на странице для сценариев */
define("ALL_LOTS_NUMBER_LOTS_PAGE", 9);
define("INDEX_NUMBER_LOTS_PAGE", 3);
define("SEARCH_NUMBER_LOTS_PAGE", 9);

/* Настройки почты */
define("USER_NAME_EMAIL_SETTING", 'doingsdone@mail.ru');
define("PASSWORD_EMAIL_SETTING", 'rds7BgcL');
define("SMTP_SERVER_NAME_EMAIL_SETTING", 'smtp.mail.ru');
define("PORT_EMAIL_SETTING", '465');
define("ENCRYPTING_EMAIL_SETTING", 'ssl');
define("CONTENT_TYPE_EMAIL_SETTING", 'text/html');

define("HTTP_NAME", 'http');
define("DOMAIN_NAME", 'yeti-cave');


?>