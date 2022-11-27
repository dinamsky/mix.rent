$( document ).ready(function() {

    var my_city_autoComplete_search_popup = new autoComplete({
        selector: 'input[name="popup_search"]',
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
            return '<div class="autocomplete-suggestion city_block" data-url="' + res[2] + '" data-header="' + item + '" data-id="' + id + '" data-val = "' + item + '" >' + item.replace(re, "<b>$1</b>") + '</div>';
        },
        onSelect: function(e, term, item){
            $('.city_block[data-id="'+item.getAttribute('data-id')+'"]').click();
            $('input[name="input_city"]').val('');
        }
    });

    $('.search_popup_trigger').on('click', function (e) {
        e.stopPropagation();
        $('#search_popup').removeClass('uk-hidden');
    });

    $('body').on('click', function () {
        if(!$('#search_popup').hasClass('uk-hidden')) $('#search_popup').addClass('uk-hidden');
    });

    $('#search_popup').on('click', function (e) {
        e.stopPropagation();
    });

    var top_carousel = $('.top_pop_carousel');

    top_carousel.on({
        'initialized.owl.carousel': function () {
             top_carousel.find('.owl-item div').css('display','block');
        }
    }).owlCarousel({
        'nav': true,
        'margin' : 5,
        'slideBy' : 4,
        'navText': ['<i uk-icon="icon:chevron-left"></i>', '<i uk-icon="icon:chevron-right"></i>'],
        // 'autoHeight': true,
        //'dots': dots,
        'items':4,
        responsive:{
            0:{

            },
            600:{

            },
            1000:{

            },
            2000:{

            }
        }

    });


});