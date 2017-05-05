<?php
require_once(__DIR__ . '/core/init.php');
$pageTitle = 'Messages';
$activeTab = 'messages';


?><!DOCTYPE html>
<html lang="en">
<head>
  <?php
  require_once('core/page-head.php');
  ?>
  <style>
  .message_not_read {
    font-weight: bold;
  }
  </style>
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
          $messages = $db->getUserMessages($user['id']);
          // echo '<pre>', print_r($messages, true), '</pre>';
          ?>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" style="table-layout:auto;">
              <tr>
                <th class="col-sm-1">De</th>
                <th class="col-sm-1">Para</th>
                <th class="col-sm-8">Asunto</th>
                <th class="col-sm-2">Recibido</th>
              </tr>
              <?php
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



  <!-- Modal -->
  <div class="modal fade" id="message-modal" role="dialog" data-messageid='0'>
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">:Message Title:</h4>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="message-modal-markasnotread">Mark as Not Read</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
