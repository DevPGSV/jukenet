<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('UTC');
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/utils.php');

if (empty($db) || !$db) die('Not connected to database!');

session_start();

$user = checkLoggedInUser();

if ($user !== false) {
  $user['age'] = floor((time() - $user['birthTimestamp']) / 60 / 60 / 24 / 365);

  $user['unread'] = 0;
  $messages = $db->getUserMessages($user['id']);
  foreach ($messages as $message) {
    if (($message['to_user.id'] === $user['id']) && !$message['isRead']) {
      $user['unread']++;
    }
  }

  $user['groups'] = $db->getUserGroups($user['id']);
  $gmessages = [];
  foreach ($db->getUserGroupMessages($user['id']) as $gmessage) {
    if (!isset($gmessages[$gmessage['to_group']])) {
      $gmessages[$gmessage['to_group']] = [];
    }
    $gmessages[$gmessage['to_group']][] = $gmessage;

  }
}

$musicGenres = $db->getMusicGenres();
