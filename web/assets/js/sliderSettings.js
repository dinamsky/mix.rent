$( document ).ready(function() {
    $(".owl-carousel-all").each(function() {
        var items = $(this).data('items');
        var dots = $(this).data('dots');
        var full = $(this).data('full');
        var ratio = $(this).data('ratio');
        if (!ratio) ratio = 2;
        var fnc = '';
        var nav = true;
        if(dots === 0){
            dots = false;
            nav = true;
            fnc = 'recount';
        }
        if(dots === 1){
            dots = true;
            nav = false;
            fnc = '';
        }
        if(dots === 2){
            dots = true;
            nav = true;
        }
        var st_padding = 20;
        if (full){
            st_padding = 0;
        }
        var margin = 0;
        if(items>1) margin = 20;
        $(this).owlCarousel({
            'nav': nav,
            'margin' : margin,
            'slideBy' : items,
            'navText': ['<i uk-icon="icon:chevron-left; ratio: '+ratio+'"></i>', '<i uk-icon="icon:chevron-right; ratio: '+ratio+'"></i>'],
            // 'autoHeight': true,
            'dots': dots,
            'loop': dots,
            'lazyLoad': true,
            'autoplay': dots,
            'autoplayTimeout': 7000,
            'autoplayHoverPause': true,
            onInitialized: recount(fnc),
            responsive:{
                0:{
                    items:1,
                    stagePadding:st_padding,
                    margin:20
                },
                600:{
                    items:items
                },
                1000:{
                    items:items
                },
                2000:{
                    items:items
                }
            }

        });
    });

    function recount(fnc)
    {
        // if (fnc === 'recount') $(".owl-carousel-all").find('div.owl-item').height($(".owl-carousel").height());
    }


    $('#page_slider .owl-nav').css('width',$('.standard_wide').width());
    $('#page_slider .owl-nav').css('bottom',$('#page_slider').height()/2+'px');

});
