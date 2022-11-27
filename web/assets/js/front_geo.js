$( document ).ready(function() {


    $.ajax({
        url: '//api.sypexgeo.net/',
        type: 'POST',
        dataType: 'json',
        success: function(json){
            $.ajax({
                url: '/ajax/frontGeo',
                type: 'POST',
                dataType: 'json',
                data: {geo:json},
                success: function(json){
                    if (confirm('Мы определили ваш город как '+json.city.name_ru)){
                        document.location.href = window.location.href;
                    }
                }
            });
        }
    });


});