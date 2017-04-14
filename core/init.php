<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__ . '/db.php');

$db = new Database('jukenet', 'jukenet', 'jukenet', 'localhost');
session_start();


if (!empty($_SESSION['user'])) {
    $user = $db->getUserDataById($_SESSION['user']);
    $user['unread'] = 1;
} else {
    $user = false;
}

// $user = ['username' => 'DevPGSV'];
