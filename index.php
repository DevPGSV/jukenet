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
      <div class="col-sm-12">
        <?php
        if ($user !== false) {
          echo '<pre>';
          $user['pw'] = '?';
          echo "<table class='table table-striped table-bordered table-hover'><thead><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>BirthTimestamp</th><th>Age</th><th>Unread</th></thead>";
          echo "<tbody><td>{$user['id']}</td><td>{$user['username']}</td><td>{$user['email']}</td><td>{$user['role']}</td><td>{$user['birthTimestamp']}</td><td>{$user['age']}</td><td>{$user['unread']}</td></tbody>";
          //print_r($user);
          //print_r($db->getUserMusicGenres($user['id']));
          echo "</table></pre>\n";
          echo "Favourite music genres:\n<ul>\n";
          foreach ($db->getUserMusicGenres($user['id']) as $g) {
            echo "  <li>$g</li>\n";
          }
          echo "</ul>\n";
        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
