<?php
header('Content-Type: text/html; charset=utf-8');

require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');

unset($_SESSION['user']);

header("Location: index.php");
?>