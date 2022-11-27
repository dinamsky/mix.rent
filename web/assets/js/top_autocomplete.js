$( document ).ready(function() {

    // var top_city_autoComplete = new autoComplete({
    //     selector: 'input[name="input_top_city"]',
    //     source: function(term, response){
    //         $.ajax({
    //             url: '/ajax/getCityByInput',
    //             type: 'POST',
    //             dataType: 'json',
    //             data: {q: term},
    //             success: function(json){
    //                 response(json);
    //             }
    //         });
    //     },
    //     renderItem: function (item, search){
    //         var res = item.split('|');
    //         item = res[0];
    //         var id = res[1];
    //         search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    //         var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
    //         return '<div class="autocomplete-suggestion" data-url="' + res[2] + '" data-header="' + item + '" data-id="' + id + '" data-val = "' + item + '" >' + item.replace(re, "<b>$1</b>") + '</div>';
    //     },
    //     onSelect: function(e, term, item){
    //         console.log(term);
    //         $('input[name="top_s_city"]').val(item.getAttribute('data-url'));
    //         $('input[name="input_top_city"]').addClass('selected');
    //     }
    // });
    //
    // var top_mark_autoComplete = new autoComplete({
    //     selector: 'input[name="input_top_mark"]',
    //     source: function(term, response){
    //         $.ajax({
    //             url: '/ajax/getMarkByInput',
    //             type: 'POST',
    //             dataType: 'json',
    //             data: {q: term, gt:$('input[name="top_s_gt"]').val()},
    //             success: function(json){
    //                 response(json);
    //             }
    //         });
    //     },
    //     renderItem: function (item, search){
    //         var res = item.split('|');
    //         item = res[0];
    //         var id = res[1];
    //         search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    //         var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
    //         return '<div class="autocomplete-suggestion" data-url="' + item + '" data-header="' + item + '" data-id="' + id + '" data-val = "' + item + '" >' + item.replace(re, "<b>$1</b>") + '</div>';
    //     },
    //     onSelect: function(e, term, item){
    //         $('input[name="top_s_mark"]').val(item.getAttribute('data-url'));
    //         $('input[name="input_top_mark"]').addClass('selected');
    //     }
    // });
    //
    // var gt_autoComplete = new autoComplete({
    //     selector: 'input[name="input_top_gt"]',
    //     source: function(term, response){
    //         $.ajax({
    //             url: '/ajax/getGtByInput',
    //             type: 'POST',
    //             dataType: 'json',
    //             data: {q: term, gt:$('#gtURL').val()},
    //             success: function(json){
    //                 response(json);
    //             }
    //         });
    //     },
    //     renderItem: function (item, search){
    //         var res = item.split('|');
    //         item = res[0];
    //         var id = res[1];
    //         search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    //         var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
    //         return '<div class="autocomplete-suggestion" data-url="' + item + '" data-header="' + item + '" data-id="' + id + '" data-val = "' + item + '" >' + item.replace(re, "<b>$1</b>") + '</div>';
    //     },
    //     onSelect: function(e, term, item){
    //         $('input[name="top_s_gt"]').val(item.getAttribute('data-id'));
    //         $('input[name="input_top_gt"]').addClass('selected');
    //     }
    // });


    var all_autoComplete = new autoComplete({
        selector: 'input[name="glob_search"]',
        source: function(term, response){
            $.ajax({
                url: '/ajax/getGlobByInput',
                type: 'POST',
                dataType: 'json',
                data: {q: term, cityUrl: $('input[name="top_s_city"]').val()},
                success: function(json){
                    response(json);
                }
            });
        },
        renderItem: function (item, search){
            var res = item.split('|');
            item = res[0];
            var url = res[1];
            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
            return '<a class="ac_link autocomplete-suggestion" href="'+url+'" data-url="' + item + '" data-header="' + item + '" data-val = "' + item + '" >' + item.replace(re, "<b>$1</b>") + '</a>';
        },
        onSelect: function(e, term, item){
            document.location.href = item.getAttribute('href');
        }
    });


    $('#go_top_search').on('click', function () {
        var city = $('input[name="top_s_city"]').val();
        var gt = $('input[name="top_s_gt"]').val();
        var mark = $('input[name="top_s_mark"]').val();

        if(!mark) mark = '';

        document.location.href = '/rent/'+city+'/all/'+gt+'/'+mark;
    });

});

