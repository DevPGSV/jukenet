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
<body>
  <?php
  require_once('core/page-navbar.php');
  ?>

  <div class="container" id="maincontainer">
    <div class="row">
      <div class="col-sm-12">
<?php
if ($user === false) :
  echo 'Only for logged in users!';
else:
?>
       <form method="POST" action="" id="choosemusicgenres-form">
         <div class="input-group">
           <select id="choosemusicgenres-form-musicgenres" class="selectpicker" data-live-search="true" title="Favourite Music Genres" multiple data-selected-text-format="count > 10" show-tick data-actions-box="true" data-header="Favourite Music Genres">
<?php
$userGenres = $db->getUserMusicGenres($user['id']);
foreach($musicGenres as $genre) {
  if (in_array($genre, $userGenres)) {
    echo "             <option selected>$genre</option>\n";
  } else {
    echo "             <option>$genre</option>\n";
  }
}
?>
            </select>
          </div>
        </form>
        <br>

        <ul class="nav nav-tabs">
<?php
foreach ($user['groups'] as $i => $group) {
  $class = '';
  if ($i === 0) {
    $class = 'active';
  }
  echo "          <li class='$class'><a data-toggle='tab' href='#group_$i'>$group</a></li>\n";
}
?>
        </ul>
        <div class="tab-content">
<?php
foreach ($user['groups'] as $i => $group) {
  $class = '';
  if ($i === 0) {
    $class = 'in active';
  }
  echo "          <div id='group_$i' class='tab-pane fade $class'>\n";
?>
          <br>
          <form method="POST" action="" class="sendgroupmessage-form">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="group" class="form-control sendgroupmessage-group" placeholder="Group" value="<?php echo $group ?>" readonly="readonly"><br>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <textarea name="text" class="form-control sendgroupmessage-text" rows="6" placeholder="Message"></textarea>
            </div>
            <button class="form-control" style="padding:0;background-color:lightgrey;"><span class="input-group-addon" style="max-width:95%;"><i class="glyphicon glyphicon-send"></i></span></button>
            <div class="alert alert-success sendgroupmessage-alert" style="display:none;">
            </div>
          </form>
          <br>
<?php
  echo '            <table class="table table-striped table-bordered table-hover editableTable">
              <thead>
                <tr><th class="col-sm-1">id</th><th class="col-sm-2">From</th><th class="col-sm-9">Text</th></tr>
              </thead>
              <tbody>'."\n";
    if (!empty($gmessages[$group])) {
      foreach ($gmessages[$group] as $gmessage) {
        echo "                <tr>";
        echo "<td>{$gmessage['id']}</td>";
        echo "<td>{$gmessage['from_username']}</td>";
        echo "<td>{$gmessage['text']}</td>";
        echo "</tr>\n";
      }
    }
  echo "              </tbody>
            </table>
          </div>\n";
}
?>
        </div>
<?php
endif;
?>
      </div>
    </div>
  </div>
</body>
</html>
