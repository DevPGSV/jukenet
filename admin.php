<?php
require_once(__DIR__ . '/core/init.php');
$pageTitle = 'Home';
$activeTab = 'home';


?><!DOCTYPE html>
<html lang="en">
<head>
  <?php
  require_once('core/page-head.php');
  ?>
</head>
</body>
  <?php
  require_once('core/page-navbar.php');
  ?>

  <div class="container-fluid" style="margin-top:50px">
    <div class="row">
      <div class="col-sm-12">
        <?php
        if ($user !== false && $User['role'] === 'admin') {
          echo 'Admin Panel!';
        } else {
          echo 'Only for admins';
        }
        $user;
        ?>
      </div>
    </div>
  </div>
</body>
</html>
