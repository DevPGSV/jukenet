<?php
$pageTitle = 'Messages';
$activeTab = 'messages';



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
        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
