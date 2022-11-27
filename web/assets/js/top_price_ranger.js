$( document ).ready(function() {

    var input0 = document.getElementById('r_from_top_price');
    var input1 = document.getElementById('r_to_top_price');
    var inputs = [input0, input1];

    var slider = document.getElementById('ranger_top_price');
    var t = $(slider);

    noUiSlider.create(slider, {
        start: [t.data('start'), t.data('finish')],
        connect: true,
        tooltips: true,
        range: {
            'min': t.data('from'),
            'max': t.data('to')
        },
        format: {
            to: function (value) {
                return Math.round(value);
            },
            from: function (value) {
                return Math.round(value);
            }
        }
    });

    slider.noUiSlider.on('update', function (values, handle) {
        inputs[handle].value = values[handle];


        var url = $('.mtf_button').attr('href').split('?');

        var new_url = url[0] + '?price_from=' + values[0] + '&price_to=' + values[1];
        $('.mtf_button').attr('href', new_url);

    });


});


