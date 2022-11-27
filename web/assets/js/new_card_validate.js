$( document ).ready(function() {



});

function new_card_validate(){


    $('input[name="price[2]"]').css('border-color','#e5e5e5');
    $('.model_placeholder').find('.select2-selection').removeClass('error');
    $('.mark_placeholder').find('.select2-selection').removeClass('error');
    $('.foto_placeholder').removeClass('error');
    $('textarea[name="content"]').css('border-color','#e5e5e5');

    var general_type = $('#generalTypeId').find('option:selected').val();
    var gt_top = $('#generalTypeTopLevelId').find('option:selected').val()-0;
    //var header = $('#new_card_form').find('input[name="header"]').val();
    var mark = $('#markId').find('option:selected').val()-0;
    var model = $('#markModelId').find('option:selected').val()-0;
    var city = $('form select[name="cityId"]').find('option:selected').val();
    var noMark = $('input[name="noMark"]').prop('checked');
    var ownMark = $('input[name="ownMark"]').val();

    var noModel = $('input[name="noModel"]').prop('checked');
    var ownModel = $('input[name="ownModel"]').val();

    var priceHour = $('input[name="price[1]"]').val();
    var priceDay = $('input[name="price[2]"]').val();

    var desc = $('textarea[name="content"]').val();

    var is_new_region = $('input[name="is_region]').prop('checked');
    var is_new_city = $('input[name="is_city]').prop('checked');

    var message = [];

    var lang = $('body').data('lang');


    if($('#new_card_form').hasClass('no_phone')){
        var phone = $('#phone').val();
        if (!phone) message.push('<br>Fill phone number!');
    }

    if ((!city || city === '0') && !is_new_city) message.push('<br>City');

    if (!general_type) message.push('<br>Vehicle type');

    //if (!header) message.push('<br>Заголовок');

    if (!mark || mark === 0) {
        if(noMark){
            if (!ownMark) message.push('<br>Type your mark');
        } else {
            message.push('<br>Mark');
            $('.mark_placeholder').find('.select2-selection').addClass('error');
        }
    }

    if (!model || model === 0) {
        if(noModel){
            if (!ownModel)  message.push('<br>Type your model');
            if (!mark && !noMark) message.push('<br>Select mark');
        } else {
            message.push('<br>Model');
            $('.model_placeholder').find('.select2-selection').addClass('error');
        }
    }

    if($('#foto_upload').val() === ''){
        message.push('<br>Fotos');
        $('.foto_placeholder').addClass('error');
    }

    if(gt_top !== 29) {
        if (!priceHour && !priceDay) {
            message.push('<br>Price');
            $('input[name="price[2]"]').css('border-color', 'red');
        }
    } else { // if this is plane
        $('input[name="price[6]"]').css('border-color','#e5e5e5');
        var price_plane = $('input[name="price[6]"]').val();
        if (!price_plane) {
            message.push('<br>Price');
            $('input[name="price[6]"]').css('border-color', 'red');
        }
    }

    if(!desc){
        message.push('<br>Description');
        $('textarea[name="content"]').css('border-color','red');
    }

    if($('#new_card_form').hasClass('unknown')){

        $('input[name="r_email"]').css('border-color','#e5e5e5');
        $('input[name="r_phone"]').css('border-color','#e5e5e5');
        $('input[name="r_password"]').css('border-color','#e5e5e5');
        $('input[name="r_header"]').css('border-color','#e5e5e5');

        var r_email = $('input[name="r_email"]').val();
        var r_password = $('input[name="r_password"]').val();
        var r_phone = $('input[name="r_phone"]').val();
        var r_header = $('input[name="r_header"]').val();

        if(r_email === '') {
            message.push('<br>Email!');
            $('input[name="r_email"]').css('border-color','red');
        }
        if(r_phone === '') {
            message.push('<br>Phone number!');
            $('input[name="r_phone"]').css('border-color','red');
        }
        if(r_password === '') {
            message.push('<br>Password!');
            $('input[name="r_password"]').css('border-color','red');
        }
        if(r_header === '') {
            message.push('<br>Name!');
            $('input[name="r_header"]').css('border-color','red');
        }
    }


    if (message.length > 0){
        $('html, body').animate({scrollTop: 0},500);
        $('#alert_content').html(message);
        UIkit.modal('#new_card_alert').show();
        //alert('Заполните поля:<br>'+message);
        return false;
    }
}