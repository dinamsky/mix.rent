$( document ).ready(function() {


    $('#button_main_search').on('click',function(){
        $('.main_search').removeAttr('hidden');
        $(this).hide();

        UIkit.update(event = 'update');
    });




});