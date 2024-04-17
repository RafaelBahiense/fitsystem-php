$(document).ready(() => {
  $("#login").click(() => {
    const client = {
      username: $("#username").val(),
      password: $("#password").val(),
    };
    $.post("login.php", client, (response) => {
      console.log(response);
    });
  });
});
