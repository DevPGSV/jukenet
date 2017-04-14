<?php
if (!$user) {
    $unreadMessages = 0;
} else {
    $unreadMessages = $user['unread'];
}

?>
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
          <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
              <a class="navbar-brand" href="index.php">JukeNet</a>
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
                      <li><a href="#" id="signup-button"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                      <li><a href="#" id="login-button"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
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
                              <li><a href="?logout"><span class="glyphicon glyphicon-log-out"></span>Log out</a></li>
                          </ul>
                      </li>
                    ';
                }
                ?>
              </ul>
          </div>
      </div>
  </nav>

  <!-- Modal -->
  <div class="modal fade" id="signup-modal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Register</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="" id="signup-form">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input id="signup-form-username" type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <input id="signup-form-email" type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input id="signup-form-password" type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input id="signup-form-password2" type="password" class="form-control" name="password2" placeholder="Repeat password">
            </div>
            <br>
            <div id="signup-form-messages" class="alert" style="display:none;">
              <a href="#" class="close" data-hide="alert" aria-label="close">×</a>
              <p></p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-default" id="signup-modal-clear">Clear</button>
          <button type="button" class="btn btn-primary" id="signup-modal-singup">Register</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="login-modal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Login</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="" id="login-form">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input id="login-form-username" type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input id="login-form-password" type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <br>
            <div id="login-form-messages" class="alert" style="display:none;">
              <a href="#" class="close" data-hide="alert" aria-label="close">×</a>
              <p></p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-default" id="login-modal-clear">Clear</button>
          <button type="button" class="btn btn-primary" id="login-modal-login">Login</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    apiurl = 'api.php';

    $("[data-hide]").on("click", function(){
        $("." + $(this).attr("data-hide")).hide();
        // -or-, see below
        // $(this).closest("." + $(this).attr("data-hide")).hide();
    });

    $('#signup-button').click(function(){
      $("#signup-modal").modal();
    });

    $('#login-button').click(function(){
      $("#login-modal").modal();
    });

    $('#signup-modal-clear').click(function(){
      $("#signup-form-username").val('');
      $("#signup-form-email").val('');
      $("#signup-form-password").val('');
      $("#signup-form-password2").val('');
    });

    $('#login-modal-clear').click(function(){
      $("#login-form-username").val('');
      $("#login-form-password").val('');
    });

    $('#signup-modal-singup').click(function(){
      $.ajax({
        type: "POST",
        url: apiurl + "?action=register",
        dataType: 'json',
        data: {
          //'username': $("#form-login input#usuario").val(),
        },
        success: function(data) {
          if (data['status'] === 'ok') {
            $("#signup-form-messages p").html(data['msg']);
            $("#signup-form-messages").removeClass("alert-danger");
            $("#signup-form-messages").addClass("alert-success");
            $("#signup-form-messages").show();

            setTimeout(function(){
              $("#signup-modal").modal('hide');
            }, 1000);
          } else {
            $("#signup-form-messages p").html(data['msg']);
            $("#signup-form-messages").removeClass("alert-success");
            $("#signup-form-messages").addClass("alert-danger");
            $("#signup-form-messages").show();
          }
        },
      });
    });

    $('#login-modal-login').click(function(){
      $.ajax({
        type: "POST",
        url: apiurl + "?action=login",
        dataType: 'json',
        data: {
          //'username': $("#form-login input#usuario").val(),
        },
        success: function(data) {
          if (data['status'] === 'ok') {
            $("#login-form-messages p").html(data['msg']);
            $("#login-form-messages").removeClass("alert-danger");
            $("#login-form-messages").addClass("alert-success");
            $("#login-form-messages").show();

            setTimeout(function(){
              $("#login-modal").modal('hide');
            }, 1000);
          } else {
            $("#login-form-messages p").html(data['msg']);
            $("#login-form-messages").removeClass("alert-success");
            $("#login-form-messages").addClass("alert-danger");
            $("#login-form-messages").show();
          }
        },
      });
    });
  </script>
