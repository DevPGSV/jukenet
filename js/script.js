function checkMessages() {

}


$(document).ready(function() {

  var apiurl = 'api.php';

  $("[data-messageid][data-action='popup_message']").on("click", function() {
    var messageEntry = this;
    $.ajax({
      type: "POST",
      url: apiurl + "?action=getMessage",
      dataType: 'json',
      data: {
        messageid: $(messageEntry).attr('data-messageid')
      },
      success: function(data) {
        console.log(data);
        if (data.status === 'ok') {
          $("#message-modal").attr('data-messageid', data.message.id);
          $("#message-modal .modal-title").text(data.message.subject);
          $("#message-modal .modal-body").html(data.message.text);
          $("#message-modal").modal();
          $(messageEntry).removeClass('message_not_read');
          $.ajax({
            type: "POST",
            url: apiurl + "?action=readMessage",
            dataType: 'json',
            data: {
              messageid: $(messageEntry).attr('data-messageid'),
              markRead: true,
            },
          });
        }
      },
    });
  });

  $('#message-modal-markasnotread').click(function() {
    var mid = $('#message-modal').attr('data-messageid');
    $.ajax({
      type: "POST",
      url: apiurl + "?action=readMessage",
      dataType: 'json',
      data: {
        messageid: mid,
        markRead: false,
      },
      success: function(data) {
        $('.message_entry[data-messageid=' + mid + ']').addClass('message_not_read');
        $('#message-modal').modal('hide');
      },
    });
  });

  $("[data-userid][data-action='popup_user']").on("click", function() {
    console.log($(this).attr('data-userid'));
  });

  $("[data-action]").css("cursor", "pointer");
  $("[data-action]").hover(function() {
    $(this).css("box-shadow", "inset 0px 0px 5px 1px blue")
  }).mouseout(function() {
    $(this).css("box-shadow", "none");
  });

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

  $('#sendMessage-user').autocomplete({
    source: 'api.php?action=searchUser',
    minLength: 1,
    select: function(event, ui) {
      console.log("Selected: " + ui.item.value + " with id: " + ui.item.id);
    },
  });
});
