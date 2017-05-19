<?php
require_once(__DIR__ . '/core/init.php');
$pageTitle = 'Admin';
$activeTab = 'admin';

$users = $db->getAllUsers();

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
        if ($user === false || $user['role'] !== 'admin'):
          echo 'You are not admin!';
        else:
          ?>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#admin_groups">Manage Groups</a></li>
            <li><a data-toggle="tab" href="#admin_users">Manage Users</a></li>
          </ul>
          <div class="tab-content">
            <div id="admin_groups" class="tab-pane fade in active">
              Groups
            </div>
            <div id="admin_users" class="tab-pane fade">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover editableTable" style="table-layout:auto;" id="admin_manageuserstable">
                  <tr>
                    <th class="col-sm-1">UID</th>
                    <th class="col-sm-8">Username</th>
                    <th class="col-sm-2">Email</th>
                    <th class="col-sm-2">Role</th>
                    <th class="col-sm-2">Birthdate</th>
                  </tr>
                  <?php
                  foreach($users as $i => $u) {
                    $birthDate = date('d/m/Y', $u['birthTimestamp']);
                    echo "<tr id='$i'>
                      <td>{$u['id']}</td>
                      <td>{$u['username']}</td>
                      <td>{$u['email']}</td>
                      <td>{$u['role']}</td>
                      <td>{$birthDate}</td>
                    </tr>";
                  }
                  ?>

                </table>
              </div>
            </div>
          </div>
          <?php
        endif;
        ?>
      </div>
    </div>
  </div>
</body>
</html>
