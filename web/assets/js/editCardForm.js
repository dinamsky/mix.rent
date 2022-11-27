$( document ).ready(function() {


    $('#subfields').on('change', '.subFieldSelect', function(){
        var subId = $(this).children('option:selected').val();
        var fieldId = $(this).data('id');
        var element = $(this);
        $.ajax({
            url: '/ajax/getSubField',
            type: 'POST',
            data: {subId:subId, fieldId:fieldId},
            success: function(html){
                console.log(html);
                if (html) {
                    $(element).attr('name', 'old').attr('class','old_select uk-select');
                    $(element).next('select').remove();
                    $(element).after(html);
                }
                else {
                    //
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    });

    $('.feature_item').on('change',function(){
        if($(this).prop('checked')) $(this).val('1').attr('checked','checked');
        else $(this).val('0').removeAttr('checked');
    });

    $('.delete_foto_button').on('click',function(){
        var t = $(this);
        var id = $(t).data('id');
        $.ajax({
            url: '/ajax/deleteFoto',
            type: 'POST',
            data: {id:id},
            success: function(html){
                if(html==='stop'){
                    alert('Нельзя удалить единственное фото!');
                } else $(t).parents('.edit_foto').remove();
            }
        });
    });

    $('.main_foto_button').on('click',function(){
        var t = $(this);
        var id = $(t).data('id');
        $.ajax({
            url: '/ajax/mainFoto',
            type: 'POST',
            data: {id:id},
            success: function(html){
                $(t).parents('.edit_foto').prependTo(".edit_fotos_block");
            }
        });
    });


    $('#show_map').on('click', function() {
        var uluru = {lat: $('#map').data('lat') - 0, lng: $('#map').data('lng') - 0};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: uluru
        });
        var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: uluru
        });

        marker.addListener('dragend', function (element) {
            console.log(marker.getPosition());
            var coords = marker.getPosition().toString();
            $('input[name="coords"]').val(coords.replace("(", "").replace(")", ""));
        });
    });


    $('#tariff_changer').on('click',function(){
        $('.hide_on_change').toggleClass('uk-hidden');
        if($('.hide_on_change').hasClass('uk-hidden')) {
            $(this).html('Отменить смену тарифа');
            $.ajax({
                url: '/user_controller_ajax_tariff_cancel',
                type: 'POST'
            });
        }
        else $(this).html('Сменить тариф');
    });

    $('.service_selector button').on('click', function(){
        $('input[name="serviceTypeId"]').val($(this).val());
        $('.service_selector button').removeClass('uk-button-primary');
        $(this).addClass('uk-button-primary');
    });

});

