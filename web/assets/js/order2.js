$( document ).ready(function() {



    $('.ord_toggler').on('click', function () {
        var id = $(this).data('id');
        var user_id = $(this).data('user_id');

        $('.active_white').remove();

        $('.ord_toggler').removeClass('active');
        $(this).addClass('active').append('<div class="active_white"></div>');
        $('.ord_content').addClass('uk-hidden').removeClass('active');
        $('#ord_desc_'+id).removeClass('uk-hidden').addClass('active');
        $('.ord_sum').addClass('uk-hidden');
        $('#ord_sum_'+id).removeClass('uk-hidden');

        $.ajax({
            url: '/ajax_admin_message_select',
            type: 'POST',
            data: {id:id},
            dataType: 'html',
            success: function (html) {
                //console.log(html);
                //document.location.href = window.location.href;
            }
        });

    });

    $('.ord_pat').on('click', function () {
        var c = $(this).html();
        $(this).parents('.msgb').find('textarea[name="answer"]').val(c).focus();
    });


    $('.dbl_edit').on('dblclick', function () {
        var i = $(this).data('i');
        var id = $(this).data('id');
        var m = $(this).html().trim();
        $('#ord_edit').find('textarea').val(m);
        $('#ord_edit').find('input[name="id"]').val(id);
        $('#ord_edit').find('input[name="i"]').val(i);
        $('#ord_edit').find('textarea').val(m);
        UIkit.modal('#ord_edit').show();
    });



});

