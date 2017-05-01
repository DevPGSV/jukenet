function checkMessages() {

}


$(document).ready(function() {

  var apiurl = 'api.php';

  $("[data-hide]").on("click", function() {
    $("." + $(this).attr("data-hide")).hide();
  });

  $('#signup-button').click(function() {
    $("#signup-modal").modal();
  });

  $('#login-button').click(function() {
    $("#login-modal").modal();
  });

  $('#signup-modal-clear').click(function() {
    $("#signup-form input").val('');
  });

  $('#login-modal-clear').click(function() {
    $("#login-form input").val('');
  });

  $('#login-modal-register').click(function() {
    $("#signup-form input#signup-form-username").val($("#login-form input#login-form-username").val());
    $("#login-modal").modal('hide');
    setTimeout(function() {
      $("#signup-modal").modal();
    }, 250);
  });

  $('#signup-modal-login').click(function() {
    $("#login-form input#login-form-username").val($("#signup-form input#signup-form-username").val());
    $("#signup-modal").modal('hide');
    setTimeout(function() {
      $("#login-modal").modal();
    }, 250);
  });

  $('#logout-button').click(function() {
    $.ajax({
      type: "POST",
      url: apiurl + "?action=logout",
      dataType: 'json',
      data: {},
      success: function(data) {
        location.reload();
      },
    });
  });

  $('#signup-modal-singup').click(function() {
    if ($("#signup-form-password").val() !== $("#signup-form-password2").val()) {
      $("#signup-form-messages p").html('Passwords doesn\'t match!');
      $("#signup-form-messages").removeClass("alert-success");
      $("#signup-form-messages").addClass("alert-danger");
      $("#signup-form-messages").show();
      return;
    }
    $.ajax({
      type: "POST",
      url: apiurl + "?action=register",
      dataType: 'json',
      data: {
        'username': $("#signup-form-username").val(),
        'email': $("#signup-form-email").val(),
        'password': $("#signup-form-password").val(),
      },
      success: function(data) {
        if (data['status'] === 'ok') {
          $("#signup-form-messages p").html(data['msg']);
          $("#signup-form-messages").removeClass("alert-danger");
          $("#signup-form-messages").addClass("alert-success");
          $("#signup-form-messages").show();

          setTimeout(function() {
            $("#signup-modal").modal('hide');
            setTimeout(function() {
              //$("#login-modal").modal();
              location.reload();
            }, 500);
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

  $('#login-modal-login').click(function() {
    $.ajax({
      type: "POST",
      url: apiurl + "?action=login",
      dataType: 'json',
      data: {
        'username': $("#login-form-username").val(),
        'password': $("#login-form-password").val(),
      },
      success: function(data) {
        if (data['status'] === 'ok') {
          $("#login-form-messages p").html(data['msg']);
          $("#login-form-messages").removeClass("alert-danger");
          $("#login-form-messages").addClass("alert-success");
          $("#login-form-messages").show();

          setTimeout(function() {
            $("#login-modal").modal('hide');
            setTimeout(function() {
              location.reload();
            }, 500);
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
});
