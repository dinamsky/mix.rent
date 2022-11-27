$( document ).ready(function() {

    $('.view_select').on('click', function () {
        $('#main_search_button').attr('data-view',$(this).val());
        document.location.href = getSelectorUrl() + getQueryVars();
    });

    $('#pager').find('button[name="page"]').on('click', function () {
        $('#main_search_button').attr('data-page',$(this).val());
        document.location.href = getSelectorUrl() + getQueryVars();
    });

    $('#onpage').on('change', function () {
        $('#main_search_button').attr('data-onpage',$(this).find('option:selected').val());
        document.location.href = getSelectorUrl() + getQueryVars();
    });

    $('#order').on('change', function () {
        $('#main_search_button').attr('data-order',$(this).find('option:selected').val());
        document.location.href = getSelectorUrl() + getQueryVars();
    });

    $('#service').on('change', function () {
        $('#main_search_button').attr('data-service',$(this).find('option:selected').val());
        document.location.href = getSelectorUrl() + getQueryVars();
    });

    $('.price_sort').on('click', function () {
        $('#order').val($(this).val());
        document.location.href = getSelectorUrl() + getQueryVars();
    });

    $('.body_more').on('click', function () {
        $('.body_type_main').hide();
        $('.body_type_all').removeAttr('hidden');
        UIkit.update(event = 'update');
    });

    $('#filter_search').on('click', function () {
        var feat = '';
        if($('#feature_form').hasClass('featured')) feat = '&' + $("#feature_form").serialize();
        document.location.href = getSelectorUrl() + getQueryVars() + '&' + $("#filter_form").serialize() + feat;
    });

    $('#filter_search_reset').on('click', function () {
        var feat = '';
        if($('#feature_form').hasClass('featured')) feat = '&' + $("#feature_form").serialize();
        document.location.href = getSelectorUrl() + getQueryVars() + feat;
    });

    $('.filter_label').on('click', function () {
        if($(this).hasClass('active')){
            $(this).parent('.filter_container').find('.filter_content').removeClass('is_expanded').addClass('is_collapsed');
            $(this).removeClass('active');
        } else {
            $(this).parent('.filter_container').find('.filter_content').removeClass('is_collapsed').addClass('is_expanded');
            $(this).addClass('active');
        }
    });

    $('.ranger').each(function () {

        var input0 = document.getElementById('r_from_'+$(this).data('id'));
        var input1 = document.getElementById('r_to_'+$(this).data('id'));
        var inputs = [input0, input1];

        var slider = document.getElementById($(this).attr('id'));
        var t = $(this);

        noUiSlider.create(slider, {
            start: [t.data('start'), t.data('finish')],
            connect: true,
            range: {
                'min': t.data('from'),
                'max': t.data('to')
            },
            format: {
              to: function ( value ) {
                return Math.round(value);
              },
              from: function ( value ) {
                return Math.round(value);
              }
            }
        });

        slider.noUiSlider.on('update', function( values, handle ) {
            inputs[handle].value = values[handle];
        });

        input0.addEventListener('keyup', function(){
            slider.noUiSlider.set([this.value, null]);
        });

        input1.addEventListener('keyup', function(){
            slider.noUiSlider.set([null, this.value]);
        });

    });

    $('#feature_search').on('click', function () {
        var filter = '';
        if($('#filter_form').hasClass('filtered')) filter = '&' + $("#filter_form").serialize();
        document.location.href = getSelectorUrl() + getQueryVars() + filter + '&' + $("#feature_form").serialize();
    });

    $('#feature_search_reset').on('click', function () {
        var filter = '';
        if($('#filter_form').hasClass('filtered')) filter = '&' + $("#filter_form").serialize();
        document.location.href = getSelectorUrl() + getQueryVars() + filter;
    });


});


