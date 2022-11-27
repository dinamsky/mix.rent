$( document ).ready(function() {
    $('#cardTabs').on('shown', function (element) {
        if (element.target.id === 'card_tab') {
            var uluru = {lat: $('#map').data('lat') - 0, lng: $('#map').data('lng') - 0};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }
    });

    $('.show_phone').on('click', function () {
        var card_id = $(this).data('card_id');
        var profile = $(this).data('profile');
        var type = 'profile';
        if (profile === 0) type = 'card';
        var t = $(this);
        $.ajax({
            url: '/ajax/showPhone',
            type: 'POST',
            data: {card_id: card_id, type: type},
            success: function (html) {
                $('.phone_block').html('<span class="opened_phone bg_green c_white uk-text-center"><i class="fa fa-phone"></i> ' + html + '</span>');
                $('.modal_phone').html(html).attr('href', 'tel:' + html);
                UIkit.modal('#user_phone_form').show();
                yaCounter43151009.reachGoal('PhoneClick', {phone: html, cardId: card_id});
            }
        });
    });

    $('.go_pro').on('click', function () {
        var user_id = $(this).data('id');
        $.ajax({
            url: '/ajax/goPro',
            type: 'POST',
            data: {user_id: user_id},
            success: function (html) {
                document.location.href = '/user/' + user_id;
            }
        });
    });


    $('#make_main_slider').on('click', function () {
        var card_id = $(this).data('id');
        $.ajax({
            //edit card controller
            url: '/ajaxMakeMainSlider',
            type: 'POST',
            data: {card_id: card_id},
            success: function () {
                document.location.href = '/card/' + card_id;
            }
        });
    });

    $('#make_best_offer').on('click', function () {
        var card_id = $(this).data('id');
        $.ajax({
            //edit card controller
            url: '/ajaxMakeBestOffer',
            type: 'POST',
            data: {card_id: card_id},
            success: function () {
                document.location.href = '/card/' + card_id;
            }
        });
    });

    // $('.show_phone_big').on('click', function () {
    //     var card_id = $(this).data('card_id');
    //     var t = $(this);
    //     $.ajax({
    //         url: '/ajax/showPhone',
    //         type: 'POST',
    //         data: {card_id:card_id, type:'profile'},
    //         success: function(html){
    //             $('.phone_block').html('<span class="opened_phone c_grey uk-text-center"><i class="fa fa-phone"></i> '+html+'</span>');
    //             $('.modal_phone').html(html);
    //             UIkit.modal('#user_phone_form').show();
    //             yaCounter43151009.reachGoal('PhoneClick', {phone: html, cardId: card_id});
    //         }
    //     });
    // });

    $('.likes').on('click', function () {
        var card_id = $(this).data('card_id');
        var t = $(this);
        $.ajax({
            url: '/ajax/plusLike',
            type: 'POST',
            data: {card_id: card_id},
            success: function (html) {
                $(t).find('i').attr('class', 'fa fa-heart c_red');
                if (html === 'ok') {
                    var l = $('#card_likes').html() - 0;
                    $('#card_likes').html(l + 1);
                }
            }
        });
    });

    $('.star').on('mouseenter', function () {
        $('.star').removeClass('hover');
        $(this).addClass('hover');
        var i = $(this).data('star');
        for (var j = 1; j < i; j++) {
            $('.star[data-star="' + j + '"]').addClass('hover');
        }
    });

    $('.star').on('mouseleave', function () {
        $('.star').removeClass('hover');
    });

    $('.star').on('click', function () {
        var i = $(this).data('star');
        $('#stars').html(i);
        $('input[name="stars"]').val(i);
        $('.star').removeClass('active');
        $(this).addClass('active');
        for (var j = 1; j < i; j++) {
            $('.star[data-star="' + j + '"]').addClass('active');
        }
    });

    if ($('.card_cover').hasClass('share')) {
        UIkit.modal('#share_butons').show();
    }


    $('.show_full_content').on('click', function () {
        $('#card_content').css('max-height', 'none').css('overflow', 'auto');
    });


    //var dat = $(this).data('res').split("-");

    $('.datepicker-reserve').datepicker.language['en'] = {
        days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        months: ['January','February','March','April','May','June', 'July','August','September','October','November','December'],
        monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        today: 'Today',
        clear: 'Clear',
        //dateFormat: 'mm/dd/yyyy',
        dateFormat: 'yyyy.mm.dd',
        timeFormat: 'hh:ii aa',
        firstDay: 0
    };

    $('.datepicker-reserve').datepicker({
        minDate: new Date(document.getElementById('user_book_form').getAttribute('data-res')),
        language: 'en'

    });

    $('#qreg_1').on('click', function () {
        //var email = $('#nrf input[name="email"]').val().trim();
        //var password = $('#nrf input[name="password"]').val().trim();
        var phone = $('#nrf input[name="phone"]').val().trim();
        var back_url = $('#nrf input[name="back_url"]').val();
        var t = $(this);

        $(this).remove();

        if(phone!=='') {
            $.ajax({
                url: '/qreg_ajax_1',
                type: 'POST',
                data: {phone: phone,back_url:back_url},
                success: function (html) {
                    if(html ==='ok') {
                        $('.rb_1').remove();
                        $('.rb_2').removeClass('uk-hidden');
                    } else {
                        UIkit.notification('User with this mobile phone number is already in Mix Rent base!',{status:'danger',timeout:100000});
                    }
                }
            });
        } else {
            UIkit.notification('All fields is required!',{status:'danger',timeout:100000});
        }
    });



    $('#qreg_2').on('click', function () {
        var regcode = $('#nrf input[name="regcode"]').val();
        var t = $(this);

        $(this).remove();

        $.ajax({
            url: '/qreg_ajax_2',
            type: 'POST',
            data: {regcode: regcode},
            success: function (html) {
                if(html!=='bad') {
                    $('.rb_2').remove();
                    $('.rb_3').removeClass('uk-hidden');
                    $('#bk_phn').remove();
                    $('#nbf_form').append('<input type="hidden" name="phone" id="phone" value="'+html+'">');
                } else {
                    UIkit.notification('Wrong code!',{status:'danger',timeout:100000});
                }
            }
        });
    });

});