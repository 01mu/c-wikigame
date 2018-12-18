<?php
/*
 * c-wikigame
 * github.com/01mu
 */

include_once 'c-wikigame.php';

$wikigame = new wikigame();

$server = '';
$username = '';
$pw = '';
$db = '';

//$wikigame->conn($server, $username, $pw, $db);
//$wikigame->create_table('popular');
//$wikigame->get_popular();

$wikigame->get_start(25, 'specific', ' Dog');
