<?php
require_once(__DIR__ . '/db.php');

function checkLoggedInUser(){
  global $db;
  if (!empty($_SESSION['user'])) {
      if ($user = $db->getUserDataById($_SESSION['user'])) {
          return $user;
      }
  }
  return false;
}
