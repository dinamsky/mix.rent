$( document ).ready(function() {
    $.ajax({
        url: '/ajax/getAllSubFields',
        type: 'POST',
        data: {generalTypeId:2},
        success: function(html){
            $('#subfields').html(html);
        }
    });
});