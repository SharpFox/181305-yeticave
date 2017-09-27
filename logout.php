<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once('init.php');

unset($_SESSION['user']);
unset($_SESSION['email']);
unset($_SESSION['userId']);
unset($_SESSION['avatarUrl']);

header("Location: index.php");
?>