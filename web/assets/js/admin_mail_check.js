$( document ).ready(function() {

    $('#check_mail_exist').on('click', function () {
        var email = $('#signUpForm input[name="email"]').val();

        $.ajax({
            url: '/admin_ajax_check_email',
            type: 'POST',
            data: {email: email},
            dataType: 'html',
            success: function (html) {
                $('#check_email_result').html(html);
            }
        });
    });

});