$(document).ready(function() {

  var apiurl = 'api.php';

  $('#choosemusicgenres-form-musicgenres').on('hidden.bs.select', function(e) {
    $.ajax({
      type: "POST",
      url: apiurl + "?action=setUserMusicGenres",
      dataType: 'json',
      data: {
        musicGenres: $('#choosemusicgenres-form-musicgenres').val(),
      },
      success: function(data) {
        if (data['status'] === 'ok') {
          location.reload();
        } else {
          alert(data['msg']);
        }
      },
    });
  });

  if ($('#admin_managegroupstable').length > 0) {
    $('#admin_managegroupstable').Tabledit({
      url: 'api.php?action=editGroup',
      editButton: false,
      restoreButton: false,
      columns: {
        identifier: [0, 'name'],
        editable: []
      },
    });
  }

  $("#addgroup-form-age").slider({
    range: true,
    min: 1,
    max: 100,
    values: [20, 30],
    slide: function(event, ui) {
      $("#addgroup-form-age").find('.ui-slider-range').html(ui.values[0] + " - " + ui.values[1]);
      $("#addgroup-form-age-d0").html(ui.values[0]);
      $("#addgroup-form-age-d1").html(ui.values[1]);
    }
  });
  $("#addgroup-form-age").find('.ui-slider-range').html($("#addgroup-form-age").slider("values", 0) + " - " + $("#addgroup-form-age").slider("values", 1));
  $("#addgroup-form-age-d0").html($("#addgroup-form-age").slider("values", 0));
  $("#addgroup-form-age-d1").html($("#addgroup-form-age").slider("values", 1));

  $('#addgroup-form').submit(function(event) {
    event.preventDefault();

    $.ajax({
      type: "POST",
      url: apiurl + "?action=addGroup",
      dataType: 'json',
      data: {
        groupname: $('#addgroup-form-groupname').val(),
        musicgenre: $('#addgroup-form-musicgenres').val(),
        minage: $("#addgroup-form-age").slider("values", 0),
        maxage: $("#addgroup-form-age").slider("values", 1),
      },
      success: function(data) {
        if (data['status'] === 'ok') {
          $('#admin_managegroupstable > tbody:last-child').append('<tr><td>' + data['groupdata']['name'] + '</td><td>' + data['groupdata']['musicgenre'] + '</td><td>' + data['groupdata']['minage'] + ' - ' + data['groupdata']['maxage'] + '</td><td style="white-space: nowrap; width: 1%;"><div class="tabledit-toolbar btn-toolbar" style="text-align: left;"> <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-delete-button btn btn-sm btn-default" style="float: none;"><span class="glyphicon glyphicon-trash"></span></button></div> <button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style="display: none; float: none;">Confirm</button></div></td></tr>');
        } else {
          alert(data['msg']);
        }
      },
    });
  });

  if ($('#admin_manageuserstable').length > 0) {
    $('#admin_manageuserstable').Tabledit({
      url: 'api.php?action=editUser',
      restoreButton: false,
      columns: {
        identifier: [0, 'id'],
        editable: [
          [2, 'email'],
          [3, 'role', '{"admin": "admin", "melomaniac": "melomaniac", "newbie": "newbie"}'],
          [4, 'birthdate'],
        ]
      },
    });
  }

  $("#signup-form-birthdate").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd/mm/yy',
    yearRange: "-150:+0",
    setDate: new Date(),
  });

  $('.selectpicker').selectpicker();

  $("#sendMessage-form").submit(function(event) {
    event.preventDefault();

    $.ajax({
      type: "POST",
      url: apiurl + "?action=sendMessage",
      dataType: 'json',
      data: {
        touser: $('#sendMessage-user').val(),
        subject: $('#sendMessage-subject').val(),
        text: $('#sendMessage-text').val(),
      },
      success: function(data) {
        if (data['status'] === 'ok') {
          $('#sendMessage-alert').removeClass('alert-danger');
          $('#sendMessage-alert').addClass('alert-success');
          $('#sendMessage-alert').html('<strong>Success!</strong> ' + data['msg']);

          //$('#sendMessage-user').val();
          $('#sendMessage-subject').val('');
          $('#sendMessage-text').val('');
        } else {
          $('#sendMessage-alert').removeClass('alert-success');
          $('#sendMessage-alert').addClass('alert-danger');
          $('#sendMessage-alert').html('<strong>Error!</strong> ' + data['msg']);
        }
        $('#sendMessage-alert').show("slow");
        setTimeout(function() {
          $('#sendMessage-alert').hide("fast");
        }, 2500);
      },
    });

  });



  $(".sendgroupmessage-form").submit(function(event) {
    event.preventDefault();
    form = $(this);

    $.ajax({
      type: "POST",
      url: apiurl + "?action=sendGroupMessage",
      dataType: 'json',
      data: {
        group: form.find('.sendgroupmessage-group').val(),
        text: form.find('.sendgroupmessage-text').val(),
      },
      success: function(data) {
        if (data['status'] === 'ok') {
          form.find('.sendgroupmessage-alert').removeClass('alert-danger');
          form.find('.sendgroupmessage-alert').addClass('alert-success');
          form.find('.sendgroupmessage-alert').html('<strong>Success!</strong> ' + data['msg']);

          form.find('.sendgroupmessage-group').val('');
          form.find('.sendgroupmessage-text').val('');

          setTimeout(function() {
            location.reload();
          }, 2500);
        } else {
          form.find('.sendgroupmessage-alert').removeClass('alert-success');
          form.find('.sendgroupmessage-alert').addClass('alert-danger');
          form.find('.sendgroupmessage-alert').html('<strong>Error!</strong> ' + data['msg']);
        }
        form.find('.sendgroupmessage-alert').show("slow");
        setTimeout(function() {
          form.find('.sendgroupmessage-alert').hide("fast");
        }, 2500);
      },
    });


  });




  $("[data-messageid][data-action='popup_message']").on("click", function() {
    var messageEntry = $(this).parent();
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
          if ($(messageEntry).hasClass('message_received')) {
            $('#message-modal-markasnotread').show();
          } else {
            $('#message-modal-markasnotread').hide();
          }
          $("#message-modal").modal();

          if ($(messageEntry).hasClass('message_not_read')) {
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
        'birthdate': $("#signup-form-birthdate").val(),
        'musicGenres': $("#signup-form-musicgenres").val(),
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
    source: 'api.php?action=searchUser&excludeCurrentUser',
    minLength: 0,
    select: function(event, ui) {
      console.log("Selected: " + ui.item.value + " with id: " + ui.item.id);
    },
  });
});
