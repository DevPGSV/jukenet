<?php
require_once(__DIR__ . '/core/init.php');
$pageTitle = 'Groups';
$activeTab = 'groups';


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
        if ($user === false) :
            echo 'Only for logged in users!';
        else:
          ?>
          <p>Groups</p>
          <?php
        endif;
        ?>
      </div>
    </div>
  </div>
</body>
</html>
