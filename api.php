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
    $answer = [
      'status' => 'ok',
      'msg' => 'test ok',
    ];
    break;
  case 'register':
    $answer = [
      'status' => 'error',
      'msg' => 'test error',
    ];
    break;
  default:
    $answer = [
      'status' => 'error',
      'msg' => 'unknown action',
    ];
    break;
}

echo json_encode($answer);
