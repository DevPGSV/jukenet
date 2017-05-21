<?php
require_once(__DIR__ . '/core/init.php');
$pageTitle = 'Admin';
$activeTab = 'admin';

$users = $db->getAllUsers();
$groups= $db->getAllGroups();

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

  <div class="container" id="maincontainer">
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
              <div>


                <form method="POST" action="" id="addgroup-form" style="max-width:900px;margin: 30px;">
                  <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                      <input id="addgroup-form-groupname" type="text" class="form-control" name="name" placeholder="Group Name" title="Group Name">
                  </div>
                  <br>
                  <div class="input-group">
                      <!--<span class="input-group-addon"><i class="glyphicon glyphicon-music"></i></span>-->
                      <select id="addgroup-form-musicgenres" class="selectpicker" data-live-search="true" title="Music Genre">
                        <?php
                        foreach($musicGenres as $genre) {
                          echo "<option>$genre</option>";
                        }
                        ?>
                      </select>
                  </div>
                  <br>
                  <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                      <div id="addgroup-form-age" title="Age Range"></div>
                      <span class="input-group-addon" id="addgroup-form-age-d0"></span>
                      <span class="input-group-addon" id="addgroup-form-age-d1"></span>
                  </div>
                  <br>
                  <div class="input-group">
                      <input id="addgroup-form-submit" type="submit" class="form-control" value="Create Group">
                  </div>

                </form>
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover editableTable" style="table-layout:auto;" id="admin_managegroupstable">
                  <thead>
                    <tr>
                      <th class="col-sm-4">Name</th>
                      <th class="col-sm-4">Music Genre</th>
                      <th class="col-sm-4">Age Range</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach($groups as $i => $g) {
                      echo "<tr id='$i'>
                        <td>{$g['name']}</td>
                        <td>{$g['musicgenre']}</td>
                        <td>{$g['minage']} - {$g['maxage']}</td>
                      </tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div id="admin_users" class="tab-pane fade">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover editableTable" style="table-layout:auto;" id="admin_manageuserstable">
                  <thead>
                  <tr>
                    <th class="col-sm-1">UID</th>
                    <th class="col-sm-3">Username</th>
                    <th class="col-sm-4">Email</th>
                    <th class="col-sm-2">Role</th>
                    <th class="col-sm-2">Birthdate</th>
                  </tr>
                  </thead>
                  <tbody>
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
                  </tbody>
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
