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

  <div class="container-fluid" style="margin-top:100px">
    <div class="row">
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
        <?php
        if (!$user) :
            echo 'Only for logged in users!';
        else:
          //$messages = $db->getUserMessages($user['id']);
          // echo '<pre>', print_r($messages, true), '</pre>';
          ?>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" style="table-layout:auto;">
              <tr>
                <th class="col-sm-1">De</th>
                <th class="col-sm-8">Asunto</th>
                <th class="col-sm-2">Recibido</th>
              </tr>
              <?php
              /*
              foreach ($messages as $message) {
                $class='';
                if (!$message['isRead']) {
                  $class .= 'message_not_read';
                }
                echo "
                <tr class='$class message_entry' data-messageid='{$message['id']}'>
                  <td data-userid='{$message['from_user.id']}' data-action='popup_user'><span data-userid='{$message['from_user.id']}'>{$message['from_user.username']}</span></td>
                  <td data-userid='{$message['to_user.id']}' data-action='popup_user'><span data-userid='{$message['to_user.id']}'>{$message['to_user.username']}</span></td>
                  <td data-messageid='{$message['id']}' data-action='popup_message'>{$message['subject']}</td>
                  <td>{$message['timestamp']}</td>
                </tr>
                ";
              }
              */
              ?>
            </table>
          </div>
          <?php
        endif;
        ?>
      </div>
      <div class="col-sm-1"></div>
    </div>
  </div>

</body>
</html>
