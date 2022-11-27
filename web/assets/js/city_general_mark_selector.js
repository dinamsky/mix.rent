$( document ).ready(function() {

    $('#countryCode').on('change',function(){
        var countryCode = $(this).find('option:selected').val();
        $.ajax({
            url: '/ajax/getRegion',
            type: 'POST',
            data: {countryCode:countryCode},
            success: function(html){
                $('#regionId').html(html);
                $('#cityId').html('');
            }
        });
    });

    $('#regionId').on('change',function(){
        $('.city_selector').html($(this).find('option:selected').html());
        var regionId = $(this).find('option:selected').val();
        $.ajax({
            url: '/ajax/getCity',
            type: 'POST',
            data: {regionId:regionId},
            success: function(html){
                $('#cityId').html(html);
            }
        });
    });

    // $('#generalTypeTopLevelId').on('change',function(){
    //     var generalTypeTopLevelId = $(this).find('option:selected').val();
    //     $('.general_selector').html($(this).find('option:selected').html());
    //     $.ajax({
    //         url: '/ajax/getGeneralTypeSecondLevel',
    //         type: 'POST',
    //         data: {generalTypeTopLevelId:generalTypeTopLevelId},
    //         success: function(html){
    //             $('#generalTypeId').html(html);
    //         }
    //     });
    // });
    //
    // $('#generalTypeId').on('change',function(){
    //     var generalTypeId = $(this).children('option:selected').val();
    //     $('.general_selector').html($(this).find('option:selected').html());
    //     // $.ajax({
    //     //     url: '/ajax/getAllSubFields',
    //     //     type: 'POST',
    //     //     data: {generalTypeId:generalTypeId},
    //     //     success: function(html){
    //     //         $('#subfields').html(html);
    //     //     }
    //     // });
    // });

    $('#markGroupName').on('change',function(){
        var groupId = $(this).find('option:selected').val();
        $('.mark_selector').html($(this).find('option:selected').html());
        $.ajax({
            url: '/ajax/getMarks',
            type: 'POST',
            data: {groupId:groupId},
            success: function(html){
                //alert(html);
                $('#markId').html(html);
            }
        });
    });

    $('#markId').on('change',function(){
        var markId = $(this).find('option:selected').val();
        $('.mark_selector').html($(this).find('option:selected').html());
        $.ajax({
            url: '/ajax/getModels',
            type: 'POST',
            data: {markId:markId},
            success: function(html){
                $('#markModelId').html(html);
            }
        });
    });

    $('#cityId').on('change', function () {
        $('.city_selector').html($(this).find('option:selected').html());
    });

    $('#markModelId').on('change', function () {
        $('.mark_selector').html($('#markId').find('option:selected').html()+' '+$(this).find('option:selected').html());
    });


    $('#main_search_button').on('click', function () {
        document.location.href = getSelectorUrl() + getQueryVars();
    });

    $('#main_search_button_left').on('click', function () {
        document.location.href = getSelectorUrl() + getQueryVars();
    });


    // $(document).click( function(event){
    //     if( $(event.target).closest('.hide_on_click_out').length || $(event.target).closest('.selector_opener').length)
    //         return;
    //     $('.hide_on_click_out').removeClass('is_open');
    //     event.stopPropagation();
    // });


    // $('body').on('click', '.city_block', function () {
    //     $('#cityURL').val($(this).data('url'));
    //     $('#cityId').val($(this).data('id'));
    //     var gtURL = $('#gtURL').val();
    //     UIkit.modal('#city_popular').hide();
    //     var cityId = $('#cityId').val();
    //     $('.city_selector').html($(this).data('header'));
    //     $.ajax({
    //         url: '/ajax/getExistMarks',
    //         type: 'POST',
    //         data: {cityId:cityId,gtURL:gtURL},
    //         success: function(html){
    //             $('#mark_placement').html(html);
    //             $.ajax({
    //                 url: '/ajax/getExistModels',
    //                 type: 'POST',
    //                 data: {markId:$('#markURL').data('id'), cityId:cityId},
    //                 success: function(html){
    //                     $('#model_placement').html(html);
    //                 }
    //             });
    //         }
    //     });
    // });

    $('body').on('click', '.city_block', function () {
        var cityId = $(this).data('id');
        $('#cityId').val(cityId);
        $('#cityURL').val($(this).data('url'));
        $('.city_selector').html($(this).data('header'));
        $.ajax({
            url: '/ajax/setCity',
            type: 'POST',
            data: {cityId:cityId},
            success: function(){
                UIkit.modal('#city_popular').hide();
                var go_href = getSelectorUrl() + getQueryVars();
                if($('body').hasClass('main_page')) go_href = '/';
                if($('body').hasClass('promo')) go_href = '/promo';
                document.location.href = go_href;
            }
        });
    });


    $('body').on('click','.mark_block', function () {
        $('#markURL').val($(this).data('url')).attr('data-id',$(this).data('id'));
        UIkit.modal('#mark_popular').hide();
        $('.mark_selector').html($(this).data('header'));
        var id = $(this).data('id');
        var cityId = $('#cityId').val();
        $.ajax({
            url: '/ajax/getExistModels',
            type: 'POST',
            data: {markId:id, cityId:cityId},
            success: function(html){
                $('#model_popular').remove();
                $('#model_placement').html(html);
            }
        });
    });

    $('body').on('click','.model_block', function () {
        $('#modelURL').val($(this).data('url'));
        UIkit.modal('#model_popular').hide();
        $('.model_selector').html($(this).data('header'));
    });




    $('.main_gt_block .gt_selector').on('click', function () {
        var gtURL = $(this).data('url');
        $('#gt_city_header').html($(this).data('header'));
        $('.gt_city_block').each(function(){
            $(this).attr('href',$(this).data('def')+'/'+gtURL);
        });
        UIkit.modal('#gt_city_popular').show();
    });

});

function getSelectorUrl(){
    var city = '/rus';
    var generalType = '/alltypes';

    var markModel = '';

    // var country = $('#countryCode').find('option:selected').data('url');
    // var regionId = $('#regionId').find('option:selected').data('url');
    // var cityId = $('#cityId').find('option:selected').data('url');

    city = '/'+$('#cityURL').val();

    var generalParent = $('#generalTypeTopLevelId').find('option:selected').data('url');
    //var general = $('#generalTypeId').find('option:selected').data('url');
    var general = $('#gtURL').val();

    var body = $('.body_header').data('body');

    // var mark = $('#markId').find('option:selected').data('url');
    var mark = $('#markURL').val();
    //var model = $('#markModelId').find('option:selected').data('url');
    var model = $('#modelURL').val();

    //var service = $('#service').find('option:selected').val();

    //if (service) service = '/'+service;
    //else service = '/all';
    var service = '/all';

    // if(country) city = '/'+country;
    // if(regionId) city = '/'+regionId;
    // if(cityId) city = '/'+cityId;

    if (generalParent) generalType = '/'+generalParent;
    if (general) generalType = '/'+general;

    if (mark && mark !== 'Любая марка') markModel = '/'+mark;
    if (mark && model && mark !== 'Любая марка' && model !== 'Любая модель') markModel = '/'+mark+'/'+model;

    if (service === '/all' && generalType === '/alltypes' && markModel === ''){
        service = '';
        generalType = '';
    }

    if (generalType === '/alltypes'){
        generalType = '';
    }

    var bodyType = '';
    if (body) bodyType = '/'+body;


    return '/rent'+city+service+generalType+markModel+bodyType;
}

function getQueryVars() {
    var view = $('#main_search_button').data('view'); view ? view = 'view='+view : view = '';
    var page = $('#main_search_button').data('page'); page ? page = '&page='+page : page = '';
    var onpage = $('#main_search_button').data('onpage'); onpage ? onpage = '&onpage='+onpage : onpage = '';
    var order = $('#order').val(); order ? order = '&order='+ order : order = '';

    var price_from = $('#r_from_price_range').val()-0;
    var pf_start = $('#r_from_price_range').data('start')-0;


    var price_to = $('#r_to_price_range').val()-0;
    var pf_end = $('#r_to_price_range').data('start')-0;

    if (price_from > pf_start  ||  price_to < pf_end) {

        price_from = '&price_from=' + price_from;
        price_to = '&price_to=' + price_to;

    } else {
        price_from = '';
        price_to = '';
    }

    if (view+page+onpage+order+price_from+price_to) return '?'+view+page+onpage+order+price_from+price_to;
    else return '';
}

