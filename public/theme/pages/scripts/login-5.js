var Login = function () {
    var handleLogin = function () {
        $('#forget-password').click(function () {
            $('#login-form').hide();
            $('.forget-form').show();
        });

        $('#back-btn').click(function () {
            $('#login-form').show();
            $('.forget-form').hide();
        });
    };
    return {
        init: function () {
            handleLogin();
            $('.forget-form').hide();

        }
    };
}();

jQuery(document).ready(function () {
    Login.init();
});

$('#login-form').submit(function (event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        beforeSend: function () {
            $("#sign-in-button").attr('disabled', true)
        },
        success: function (response, status, xhr, $form) {
            $("#sign-in-button").removeAttr('disabled');
            displayToastr(null, $('#login-form').attr('data-success'), 'success', null);
            setTimeout(function(){
                location.reload();
            },3000);
        },
        error: function (xhr, status, error) {
            $("#sign-in-button").removeAttr('disabled');
            let data = JSON.parse(xhr.responseText);
            let errors = '';
            $.each(data.errors, function(index, error) {
                errors += error + '<br/>';
            });
            displayToastr(null, errors, 'error', null);
        }
    })
});
