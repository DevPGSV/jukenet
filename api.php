<?php
require_once(__DIR__ . '/core/init.php');

$answer = [
  'status' => 'error',
  'msg' => 'unkown error',
];

if (empty($_GET['action'])) {
    $_GET['action'] = '';
}

switch ($_GET['action']) {
  case 'login':
    $u = $db->getUserDataByUsername($_POST['username']);
    if ($u === false) {
      $u = $db->getUserDataByEmail($_POST['username']);
    }
    if ($u === false) {
      $answer = [
        'status' => 'error',
        'msg' => 'user_doesnt_exist',
      ];
    } else {
      if (password_verify($_POST['password'], $u['pw'])) {
        $_SESSION['user'] = $u['id'];
        $answer = [
          'status' => 'ok',
          'msg' => 'user_loggedin',
        ];
      } else {
        $answer = [
          'status' => 'error',
          'msg' => 'password_incorrect',
        ];
      }
    }
    break;
  case 'register':
    if ($db->getUserDataByUsername($_POST['username']) !== false) {
      $answer = [
        'status' => 'error',
        'msg' => 'user_exists',
      ];
    } else if ($db->getUserDataByEmail($_POST['email']) !== false) {
      $answer = [
        'status' => 'error',
        'msg' => 'email_exists',
      ];
    } else if ($uid = $db->registerNewUser($_POST['username'], password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]), $_POST['email'])) {
      $_SESSION['user'] = $uid;
      $answer = [
        'status' => 'ok',
        'msg' => 'user_registered',
      ];
    } else {
      $answer = [
        'status' => 'error',
        'msg' => 'unknown',
      ];
    }
    break;
  case 'logout':
    $_SESSION['user'] = '';
    unset($_SESSION['user']);
    $answer = [
      'status' => 'ok',
      'msg' => 'logged_out',
    ];
    break;
  case 'getMessage':
    if (!$user) {
      $answer = [
        'status' => 'error',
        'msg' => 'not_logged_in',
      ];
    } else {
      $message = $db->getMessageById($_POST['messageid']);
      $answer = [
        'status' => 'ok',
        'msg' => 'message_obtained',
        'message' => $message,
      ];
    }
    break;
  case 'readMessage':
    if (!$user) {
      $answer = [
        'status' => 'error',
        'msg' => 'not_logged_in',
      ];
    } else {
      $_POST['markRead'] = $_POST['markRead'] == 'true';
      $message = $db->markMessageReadById((int)$_POST['messageid'], $_POST['markRead']);
      $answer = [
        'status' => 'ok',
        'msg' => 'message_marked',
      ];
    }
    break;
  default:
    $answer = [
      'status' => 'error',
      'msg' => 'unknown_action',
    ];
    break;
}

echo json_encode($answer);
