<?php
require_once(__DIR__ . '/core/init.php');

header('Content-Type: application/json');

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
    } else if ($uid = $db->registerNewUser($_POST['username'], password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]), $_POST['email'], DateTime::createFromFormat('d/m/Y', $_POST['birthdate'])->format("U"))) {
      $_SESSION['user'] = $uid;
      $db->setUserMusicGenres($uid, $_POST['musicGenres']);
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
  case 'searchUser':
    $search = $_GET['term'];
    $answer = $db->searchUsers($search.'%');
    if ($_GET['excludeCurrentUser'] && ($user !== false)) {
      if (in_array($user['username'], $answer)) {
        array_diff($answer, [$user['username']]);
      }
    }
    break;
  case 'sendMessage':
    if (empty($_POST['touser']) || empty($_POST['subject']) || empty($_POST['text'])) {
      $answer = [
        'status' => 'error',
        'msg' => 'empty_fields',
      ];
    } else if (!$user) {
      $answer = [
        'status' => 'error',
        'msg' => 'not_logged_in',
      ];
    } else {
      $timestamp = time();
      if ($toUser = $db->getUserDataByUsername($_POST['touser'])) {
        if($db->sendPrivateMessage($user['id'], $toUser['id'], $_POST['subject'], $_POST['text'], $timestamp, false, NULL)) {
          $answer = [
            'status' => 'ok',
            'msg' => 'message_sent',
          ];
        } else {
          $answer = [
            'status' => 'error',
            'msg' => 'unknown_error',
          ];
        }
      } else {
        $answer = [
          'status' => 'error',
          'msg' => 'user_not_found',
        ];
      }
    }
    break;
  case 'editUser':
    
    break;
  default:
    $answer = [
      'status' => 'error',
      'msg' => 'unknown_action',
    ];
    break;
}

echo json_encode($answer);
