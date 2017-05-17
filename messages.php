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
        if ($user === false) :
            echo 'Only for logged in users!';
        else:
          ?>
          <form method="POST" id="sendMessage-form">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="touser" class="form-control" placeholder="User" id="sendMessage-user"><br>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                <input name="subject" class="form-control" placeholder="Subject" id="sendMessage-subject"><br>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <textarea name="text" class="form-control" rows="6" placeholder="Message" id="sendMessage-text"></textarea>
            </div>
            <button class="form-control" style="padding:0;background-color:lightgrey;"><span class="input-group-addon" style="max-width:95%;"><i class="glyphicon glyphicon-send"></i></span></button>
            <div class="alert alert-success" style="display:none;" id="sendMessage-alert">
            </div>

          </form>
          <br><br>
          <?php

          // echo '<pre>', print_r($messages, true), '</pre>';
          ?>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#messages_recibidos">Recibidos</a></li>
            <li><a data-toggle="tab" href="#messages_sent">Enviados</a></li>
          </ul>
          <div class="tab-content">
            <div id="messages_recibidos" class="tab-pane fade in active">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" style="table-layout:auto;">
                  <tr>
                    <th class="col-sm-1">De</th>
                    <th class="col-sm-8">Asunto</th>
                    <th class="col-sm-2">Fecha</th>
                  </tr>
                  <?php
                  foreach ($messages as $message) {
                    if ($message['to_user.id'] !== $user['id']) continue;
                    $class='';
                    if (!$message['isRead']) {
                      $class .= 'message_not_read';
                    }
                    $fecha = date("F j, Y, g:i a", $message['timestamp']);
                    echo "
                    <tr class='$class message_entry message_received' data-messageid='{$message['id']}'>
                      <td data-userid='{$message['from_user.id']}' data-action='popup_user'><span data-userid='{$message['from_user.id']}'>{$message['from_user.username']}</span></td>
                      <td data-messageid='{$message['id']}' data-action='popup_message'>{$message['subject']}</td>
                      <td>{$fecha}</td>
                    </tr>
                    ";
                  }

                  ?>
                </table>
              </div>
            </div>
            <div id="messages_sent" class="tab-pane fade">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" style="table-layout:auto;">
                  <tr>
                    <th class="col-sm-1">Para</th>
                    <th class="col-sm-8">Asunto</th>
                    <th class="col-sm-2">Fecha</th>
                  </tr>
                  <?php
                  foreach ($messages as $message) {
                    if ($message['from_user.id'] !== $user['id']) continue;
                    $fecha = date("F j, Y, g:i a", $message['timestamp']);
                    echo "
                    <tr class='message_entry message_sent' data-messageid='{$message['id']}'>
                      <td data-userid='{$message['to_user.id']}' data-action='popup_user'><span data-userid='{$message['to_user.id']}'>{$message['to_user.username']}</span></td>
                      <td data-messageid='{$message['id']}' data-action='popup_message'>{$message['subject']}</td>
                      <td>{$fecha}</td>
                    </tr>
                    ";
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
          <button type="button" class="btn btn-default" id="message-modal-markasnotread" style="display:none;">Mark as Not Read</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
