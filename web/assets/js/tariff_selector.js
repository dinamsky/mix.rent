$( document ).ready(function() {
    $('.selector_item').on('click', function () {
        var tariff_id = $(this).data('id');
        $('.tariff_block').removeClass('active');
        $('.selector_item').removeClass('active');
        $('#tariff_'+tariff_id).addClass('active');
        $('.selector_item.t_'+tariff_id).addClass('active');
        $('input[name="tariffId"]').val(tariff_id);
        $('.temp_button').remove();
    });
});