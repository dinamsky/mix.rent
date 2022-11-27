$( document ).ready(function() {

    $('body').on('click', '.city_block_left_block', function () {
        $('#cityURL').val($(this).data('url'));
        $('#cityId').val($(this).data('id'));
        var gtURL = $('#gtURL').val();
        UIkit.modal('#city_popular_left').hide();

        var cityId = $('#cityId').val();
        $('.city_selector_left').html($(this).data('header'));

        $.ajax({
            url: '/ajax/getExistMarksLeft',
            type: 'POST',
            data: {cityId:cityId,gtURL:gtURL},
            success: function(html){
                $('#mark_placement_left').html(html);
                //$('#mark_placement_left .mark_block').addClass('mark_block_left_block').removeClass('mark_block');
                $.ajax({
                    url: '/ajax/getExistModelsLeft',
                    type: 'POST',
                    data: {markId:$('#markURL').data('id'), cityId:cityId},
                    success: function(html){
                        $('#model_placement_left').html(html);
                        //$('#model_placement_left .model_block').addClass('model_block_left_block').removeClass('model_block');
                        UIkit.offcanvas('#left_bar').show();
                    }
                });
            }
        });
    });


    $('body').on('click','.mark_block_left_block', function () {
        $('#markURL').val($(this).data('url')).attr('data-id',$(this).data('id'));
        UIkit.modal('#mark_popular_left').hide();


        $('.mark_selector_left').html($(this).data('header'));

        var id = $(this).data('id');
        var cityId = $('#cityId').val();
        $.ajax({
            url: '/ajax/getExistModelsLeft',
            type: 'POST',
            data: {markId:id, cityId:cityId},
            success: function(html){
                $('#model_placement_left').html(html);
                //$('#model_placement_left .model_block').addClass('model_block_left_block').removeClass('model_block');
                UIkit.offcanvas('#left_bar').show();
            }
        });
    });

    $('body').on('click','.model_block_left_block', function () {
        UIkit.offcanvas('#left_bar').show();
        $('#modelURL').val($(this).data('url'));
        UIkit.modal('#model_popular_left').hide();
        $('.model_selector_left').html($(this).data('header'));

    });

});




