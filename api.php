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
  case 'setUserMusicGenres':
    if (!$user) {
      $answer = [
        'status' => 'error',
        'msg' => 'not_logged_in',
      ];
    } else {
      if (empty($_POST['musicGenres'])) $_POST['musicGenres'] = [];
      if ($db->setUserMusicGenres($user['id'], $_POST['musicGenres'])) {
        $answer = [
          'status' => 'ok',
          'msg' => 'music_genres_saved',
        ];
      } else {
        $answer = [
          'status' => 'error',
          'msg' => 'music_genres_not_saved',
        ];
      }
    }
    break;
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
    $uid = $_POST['id'];
    if ($_POST['action'] === 'delete') {
      if ($db->deleteUser($uid)) {
        $answer = [
          'status' => 'ok',
          'msg' => 'user_deleted',
        ];
      } else {
        http_response_code(409);
        $answer = [
          'status' => 'error',
          'msg' => 'error_deleting_user',
        ];
      }
    } else if ($_POST['action'] === 'edit') {
      $uemail = $_POST['email'];
      $urole = $_POST['role'];
      $ubirthdate = $_POST['birthdate'];
      $birthdate = DateTime::createFromFormat('d/m/Y', $ubirthdate);
      if ($birthdate === false) {
        http_response_code(409);
        $answer = [
          'status' => 'error',
          'msg' => 'invalid_date',
        ];
      } else {
        $birthTimestamp = $birthdate->format("U");
        try {
          $status = $db->editUserData($uid, $uemail, $urole, $ubirthdate);
        } catch(Exception $e) {
          $status = false;
        }
        if ($status) {
          $answer = [
            'status' => 'ok',
            'msg' => 'user_edited',
          ];
        } else {
          http_response_code(409);
          $answer = [
            'status' => 'error',
            'msg' => 'user_not_edited',
          ];
        }
      }
    }
    break;
  case 'addGroup':
    $gname = $_POST['groupname'];
    $gmusicgenre = $_POST['musicgenre'];
    $gminage = $_POST['minage'];
    $gmaxage = $_POST['maxage'];
    if ($db->getGroupData($gname) !== false) {
      $answer = [
        'status' => 'error',
        'msg' => 'group_name_exists',
      ];
    } elseif ($db->addGroup($gname, $gmusicgenre, $gminage, $gmaxage)) {
      if ($db->addUsersToGroup($gname)) {
        $answer = [
          'status' => 'ok',
          'msg' => 'group_added',
          'groupdata' => [
            'name' => $gname,
            'musicgenre' => $gmusicgenre,
            'minage' => $gminage,
            'maxage' => $gmaxage,
          ]
        ];
      } else {
        $answer = [
          'status' => 'error',
          'msg' => 'users_not_added_to_group',
        ];
      }
    } else {
      $answer = [
        'status' => 'error',
        'msg' => 'group_not_added',
      ];
    }
    break;
  case 'editGroup':
    if ($_POST['action'] === 'delete') {
      $gname = $_POST['name'];
      if ($db->deleteGroup($gname)) {
        $answer = [
          'status' => 'ok',
          'msg' => 'group_ndeleted',
        ];
      } else {
        $answer = [
          'status' => 'error',
          'msg' => 'group_not_deleted',
        ];
      }
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
