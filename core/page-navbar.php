<?php
$user = false;
if (!$user) {
    $unreadMessages = 0;
} else {
    $unreadMessages = $user['unread'];
}
$user = ['username' => 'DevPGSV'];
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
          <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
              <a class="navbar-brand" href="#">WebSiteName</a>
          </div>
          <div class="collapse navbar-collapse" id="myNavbar">
              <ul class="nav navbar-nav">
                  <li class="<?php echo ($activeTab === 'home')? 'active' : ''; ?>"><a href="index.php">Home</a></li>
                  <li class="<?php echo ($activeTab === 'messages')? 'active' : ''; ?>"><a href="messages.php">Messages<?php echo ($unreadMessages > 0) ? " <span class='badge'>$unreadMessages</span>" : ''; ?></a></li>
              </ul>
              <!--<p class="navbar-text">Some text</p>-->

              <form class="navbar-form navbar-left">
                  <div class="input-group">
                      <input type="text" class="form-control" placeholder="Search Music">
                      <div class="input-group-btn">
                      <button class="btn btn-default" type="submit">
                          <i class="glyphicon glyphicon-search"></i>
                      </button>
                      </div>
                  </div>
              </form>

              <ul class="nav navbar-nav navbar-right">
                <?php
                if (!$user) {
                    echo '
                      <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                      <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    ';
                } else {
                    echo '
                      <li class="dropdown">
                          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> '.$user['username'].'
                              <span class="caret"></span>
                          </a>
                          <ul class="dropdown-menu">
                              <li><a href="#">O1</a></li>
                              <li><a href="#">O2</a></li>
                              <li><a href="?logout">Log out</a></li>
                          </ul>
                      </li>
                    ';
                }
                ?>
              </ul>
          </div>
      </div>
  </nav>
