$( document ).ready(function() {



    $('#owner_accept').on('click', function () {
        var id = $(this).val();

        $.ajax({
            url: '/ajax_owner_accept',
            type: 'POST',
            data: {id:id},
            dataType: 'html',
            success: function (html) {
                console.log(html);
                document.location.href = window.location.href;
            },
            error: function (html) {
                console.log(html);

            }
        });
    });

    $('#owner_reject').on('click', function () {
        var id = $(this).val();

        $.ajax({
            url: '/ajax_owner_reject',
            type: 'POST',
            data: {id:id},
            dataType: 'html',
            success: function (html) {
                //console.log(html);
                document.location.href = window.location.href;
            }
        });
    });

    $('#owner_answer').on('click', function () {
        var id = $(this).val();
        var answer = $(this).parents('.ord_content').find('textarea[name="answer"]').val();

        $.ajax({
            url: '/ajax_owner_answer',
            type: 'POST',
            data: {id:id,answer:answer},
            dataType: 'html',
            success: function (html) {
                //console.log(html);
                document.location.href = window.location.href;
            }
        });
    });

    $('#renter_answer').on('click', function () {
        var id = $(this).val();
        var answer = $(this).parents('.ord_content').find('textarea[name="answer"]').val();

        $.ajax({
            url: '/ajax_renter_answer',
            type: 'POST',
            data: {id:id,answer:answer},
            dataType: 'html',
            success: function (html) {
                //console.log(html);
                document.location.href = window.location.href;
            }
        });
    });

    $('#owner_pincode').on('click', function () {
        var id = $(this).val();
        var pincode = $('input[name="owner_pincode"]').val();

        $.ajax({
            url: '/ajax_owner_pincode',
            type: 'POST',
            data: {id:id,pincode:pincode},
            dataType: 'html',
            success: function (html) {
                //console.log(html);
                document.location.href = window.location.href;
            }
        });
    });

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

        if($('body').hasClass('mobile')){
            UIkit.offcanvas('#ofc_left').hide();
        }

        $.ajax({
            url: '/ajax_set_ord_active',
            type: 'POST',
            data: {id:id,user_id:user_id},
            dataType: 'html',
            success: function () {
                //document.location.href = window.location.href;
            }
        });
    });

    $('.ord_pat').on('click', function () {
        var c = $(this).html();
        $(this).parents('.msgb').find('textarea[name="answer"]').val(c).focus();
    });

    // var timer = '';
    //
    // if(!$('textarea[name="answer"]').is(":focus")){
    //
    //     console.log('start');
    // }

    // var timer = setInterval(function(){
    //     var t = $('textarea[name="answer"]');
    //     if(!t.is(":focus") && t.val() === '') {
    //         //console.log('not in focus');
    //         window.location.reload(true);
    //     } else {
    //         //console.log('in focus');
    //     }
    //
    //     }, 10000);

    if(!$('body').hasClass('mobile')) {

        $('.ord_messages').css('height', $(window).height() - 300 + 'px');
        $('.lft_blk').css('height', $('.ord_central').height() - 50 + 'px');

    } else {
        $('#content').css('min-height', $(window).height() - 58 + 'px');
    }

    $('.ord_content textarea').on('keyup',function () {
        var c = $(this).val();
        if (c!=='')  $(this).parents('.ord_content').find('.snd_btn').removeAttr('disabled');
        else $(this).parents('.ord_content').find('.snd_btn').attr('disabled',true);
    });


    $('.rs_selector').on('mouseenter', function () {
        var s = $(this).data('star')-0;
        var t = $(this);
        $(this).parent('div').find('.rs_selector').removeClass('filled');
        for (var i = 1; i <= s; i++) {
           t.parent('div').find('.star_'+i).addClass('filled');
        }

    });

    $('.rs_selector').on('mouseleave', function () {
        var t = $(this);
        var s = t.parent('div').find('.rs_selector.stopped').data('star')-0;
        $(this).parent('div').find('.rs_selector').removeClass('filled');
        for (var i = 1; i <= s; i++) {
           t.parent('div').find('.star_'+i).addClass('filled');
        }
    });

    $('.rs_selector').on('click', function () {
        var t = $(this);
        var s = $(this).data('star')-0;

        t.parents('form').find('input[name="rating"]').val(s);
        t.parent('div').find('.rs_selector').removeClass('stopped');
        t.addClass('stopped');
    });

    //console.log($(window).height());

    // $('textarea[name="answer"]').on('focus', function () {
    //     clearTimeout(timer);
    //     console.log('stop');
    // });



    // $('#owner_finish').on('click', function () {
    //     var id = $(this).val();
    //     $.ajax({
    //         url: '/ajax_owner_finish',
    //         type: 'POST',
    //         data: {id:id},
    //         dataType: 'html',
    //         success: function (html) {
    //             //console.log(html);
    //             document.location.href = window.location.href;
    //         }
    //     });
    // });
    //
    // $('#renter_finish').on('click', function () {
    //     var id = $(this).val();
    //     $.ajax({
    //         url: '/ajax_renter_finish',
    //         type: 'POST',
    //         data: {id:id},
    //         dataType: 'html',
    //         success: function (html) {
    //             console.log(html);
    //             //document.location.href = window.location.href;
    //         }
    //     });
    // });
});

$(document)
    .one('focus.autoExpand', 'textarea.autoExpand', function(){
        var savedValue = this.value;
        this.value = '';
        this.baseScrollHeight = this.scrollHeight;
        this.value = savedValue;
    })
    .on('input.autoExpand', 'textarea.autoExpand', function(){
        var minRows = this.getAttribute('data-min-rows')|0, rows;
        this.rows = minRows;
        rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 16);
        this.rows = minRows + rows;
    });

