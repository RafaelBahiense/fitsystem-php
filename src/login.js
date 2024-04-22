$(document).ready(() => {
  $("#login").click(() => {
    const client = {
      username: $("#username").val(),
      password: $("#password").val(),
    };

    $.ajax({
      type: "POST",
      url: "login.php",
      data: client,
      dataType: "json",
      success: function(response) {
        if (response.redirect) {
          window.location.href = response.redirect;
        } else if (response.error) {
          $("#error").text(response.error);
        }
      },
      error: function() {
        $("#error").text("An error occurred during the request.");
      }
    });
  });
});
