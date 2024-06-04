$(document).ready(() => {
    $('#login').click(() => {
        const client = {
            username: $('#username').val(),
            password: $('#password').val(),
        };

        $.ajax({
            type: 'POST',
            url: 'login.php',
            data: JSON.stringify(client),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function (response) {
                alert(response.responseJSON.error);
            },
        });
    });
});
