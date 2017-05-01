<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/utils.php');

if (empty($db) || !$db) die('Not connected to database!');

session_start();

$user = checkLoggedInUser();
if ($user !== false) {
  $user['unread'] = 1;
}

//$user = ['username' => 'DevPGSV', 'unread' => 1];
