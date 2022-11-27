$( document ).ready(function() {


    var my_city_autoComplete = new autoComplete({
        selector: 'input[name="input_city"]',
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
            return '<div class="autocomplete-suggestion city_block" data-url="' + res[2] + '" data-header="' + item + '" data-id="' + id + '" data-val = "' + id + '" >' + item.replace(re, "<b>$1</b>") + '</div>';
        },
        onSelect: function(e, term, item){
            $('.city_block[data-id="'+term+'"]').click();
            $('input[name="input_city"]').val('');
        }
    });


    $('.city_helper_close').on('click',function(){
        $(this).parents('.city_helper').remove();
    });
    


    var my_city_autoComplete2 = new autoComplete({
        selector: 'input[name="left_city_input"]',
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
            $('#left_city_input_url').val(item.getAttribute('data-url'));
            var hr = '/rent/'+item.getAttribute('data-url')+'/all/'+$('#left_gt_select option:selected').val();
            $('.mtf_button').attr('href',hr);
        }
    });


    $('#left_gt_select').on('change',function(){
        var hr = '/rent/'+$('#left_city_input_url').val()+'/all/'+$('#left_gt_select option:selected').val();
            $('.mtf_button').attr('href',hr);
    });

});

