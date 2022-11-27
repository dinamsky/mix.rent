$( document ).ready(function() {


    $('#promo_count').on('click', function () {

        var mark = $('#markId').find('option:selected').html();
        var model = $('#markModelId').find('option:selected').html();
        var modelId = $('#markModelId').find('option:selected').val();
        var days = $('#dayz').val()-0;

        $('#example').addClass('uk-hidden');

        $.ajax({
            url: '/promo_ajax_counter',
            type: 'POST',
            data: {modelId: modelId, days:days},
            dataType: 'json',
            success: function (json) {
                if(! json.error) {
                    $('.promo_result_car').html(mark + ' ' + model);
                    $('#promo_result_day').html(Math.round(json.price/60));
                    $('#promo_result_dayz').html(days);
                    $('#promo_result_total').html(Math.round(json.price * days/60));
                    $('#promo_result_slider').html(json.slider);
                    $('#nothing').addClass('uk-hidden');
                    $('#counted').addClass('uk-hidden');
                    $('#counted').removeClass('uk-hidden');
                    $(".owl-carousel-all").each(function () {
                        var items = $(this).data('items');
                        var dots = $(this).data('dots');
                        var full = $(this).data('full');
                        var fnc = '';
                        if (dots === 0) {
                            dots = false;
                            fnc = 'recount';
                        } else {
                            dots = true;
                            fnc = '';
                        }
                        var st_padding = 50;
                        if (full) {
                            st_padding = 0;
                        }
                        var margin = 0;
                        if (items > 1) margin = 20;

                        $(this).owlCarousel({
                            'nav': true,
                            'margin': margin,
                            'slideBy': items,
                            'navText': ['<i uk-icon="icon:chevron-left"></i>', '<i uk-icon="icon:chevron-right"></i>'],

                            'dots': dots

                        });
                    });
                } else {
                    $('#counted').addClass('uk-hidden');
                    $('#nothing').removeClass('uk-hidden');
                }

            }
        });
    });

    $('#promo_add_case').on('click', function () {
        var p_case = $('#promo_case').val();
        $('#promo_case_list').append('<br>- '+$('#promo_case').val());
        $('#promo_case').val('');

        $.ajax({
            url: '/promo_ajax_case',
            type: 'POST',
            data: {p_case: p_case},
            dataType: 'html',
            success: function (html) {
                console.log(html);
            }
        });
    });

    $('.promo_plus').on('click', function () {
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $(this).html('<i uk-icon="plus"></i>');
        } else {
            $(this).addClass('active');
            $(this).html('<i uk-icon="close"></i>');
        }
    });


    $('#b8_mark').on('change',function(){
        var markId = $(this).find('option:selected').val();
        $('.mark_selector').html($(this).find('option:selected').html());
        $.ajax({
            url: '/ajax/getModels',
            type: 'POST',
            data: {markId:markId},
            success: function(html){
                $('.block_8').find('.uk-hidden').removeClass('uk-hidden');
                $('#b8_model').html(html);
            }
        });
    });

    var my_promo_city_autoComplete = new autoComplete({
        selector: 'input[name="promo_city"]',
        source: function(term, response){
            $.ajax({
                url: '/ajax/getCityByInput',
                type: 'POST',
                dataType: 'json',
                data: {q: term},
                success: function(json){

                    response(json);
                }
            });
        },
        renderItem: function (item, search){
            var res = item.split('|');
            item = res[0];
            var id = res[1];
            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
            return '<div class="autocomplete-suggestion " data-url="' + res[2] + '" data-header="' + item + '" data-id="' + id + '" data-val = "' + item + '" >' + item.replace(re, "<b>$1</b>") + '</div>';
        },
        onSelect: function(e, term, item){
            //$('.city_block[data-id="'+term+'"]').click();
            //$('input[name="input_city"]').val('');
        }
    });



});

function promo_mark_model_names() {
    $('input[name="promo_mark"]').val($('select[name="promo_mark_sel"] option:selected').html().trim());
    $('input[name="promo_model"]').val($('select[name="promo_model_sel"] option:selected').html());
    return true;
}