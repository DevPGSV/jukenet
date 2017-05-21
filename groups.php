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

  <div class="container-fluid" style="margin-top:60px">
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
    echo "<option selected>$genre</option>\n";
  } else {
    echo "<option>$genre</option>\n";
  }
}
?>
              </select>
            </div>
          </form>
          <br>
          <table class="table table-striped table-bordered table-hover editableTable">
            <thead>
              <tr>
                <td>id</td>
                <td>From</td>
                <td>To Group</td>
                <td>Text</td>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($gmessages as $gmessage) {
                echo "<tr>";
                echo "<td>{$gmessage['group_messages.id']}</td>";
                echo "<td>{$gmessage['group_messages.from_user']}</td>";
                echo "<td>{$gmessage['group_messages.to_group']}</td>";
                echo "<td>{$gmessage['group_messages.text']}</td>";
                echo "</tr>\n";
              }
              ?>
            </tbody>
          </table>
          <?php
        endif;
        ?>
      </div>
    </div>
  </div>
</body>
</html>
