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
<body>
  <?php
  require_once('core/page-navbar.php');
  ?>

  <div class="container" id="maincontainer">
    <div class="row">
      <div class="col-sm-12">
        <?php
        if (!$user) :
            echo 'Only for logged in users!';
        else:
          ?>
          <form method="POST" id="sendBroadcast-form">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <textarea name="text" class="form-control" rows="6" placeholder="Message" id="sendBroadcast-text"></textarea>
            </div>
            <button class="form-control" style="padding:0;background-color:lightgrey;"><span class="input-group-addon" style="max-width:95%;"><i class="glyphicon glyphicon-send"></i></span></button>
            <div class="alert alert-success" style="display:none;" id="sendBroadcast-alert">
            </div>
          </form>
          <br><br>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" style="table-layout:auto;">
              <thead>
                <tr>
                  <th class="col-sm-1">De</th>
                  <th class="col-sm-8">Texto</th>
                  <th class="col-sm-2">Recibido</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($broadcasts as $broadcast) {
                  $fecha = date("F j, Y, g:i a", $broadcast['timestamp']);
                  echo "
                  <tr class='broadcast_entry' data-broadcastid='{$broadcast['id']}'>
                    <td data-userid='{$broadcast['from_id']}' data-action='popup_user'><span data-userid='{$broadcast['from_id']}'>{$broadcast['from_username']}</span></td>
                    <td data-broadcastid='{$broadcast['id']}' data-action='popup_broadcast'>{$broadcast['text']}</td>
                    <td>{$fecha}</td>
                  </tr>
                  ";
                }
                ?>
              </tbody>
            </table>
          </div>
          <?php
        endif;
        ?>
      </div>
    </div>
  </div>

</body>
</html>
