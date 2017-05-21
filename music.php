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
<body>
  <?php
  require_once('core/page-navbar.php');
  ?>

  <div class="container-fluid" id="maincontainer">
    <div class="row">
      <div class="col-sm-2">
        <?php
        if (!empty($_GET['search'])) {
            echo htmlentities($_GET['search']);
        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
