$( document ).ready(function() {

    $('#add_slide').on('click', function () {
        var tpl = $('#slide_tpl').html();

        $('#add_slide').before(tpl);
        $('#slider_form').submit();
    });

    $('#slider_form').on('click','.delslide', function () {


        $(this).parents('.slrow').remove();
        $('#slider_form').submit();
    });
});