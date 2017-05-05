<?php
require_once(__DIR__ . '/core/init.php');
$pageTitle = 'Broadcasts';
$activeTab = 'broadcasts';


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
      <div class="col-sm-2">
        <?php
        if (!$user) {
            echo 'Only for logged in users!';
        } else {
            echo 'Broadcasts here :)';
        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
