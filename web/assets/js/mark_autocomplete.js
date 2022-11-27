$( document ).ready(function() {


    var my_autoComplete = new autoComplete({
        selector: 'input[name="input_mark"]',
        source: function(term, response){
            $.ajax({
                url: '/ajax/getMarkByInput',
                type: 'POST',
                dataType: 'json',
                data: {q: term, gt:$('#gtURL').val()},
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
            return '<div class="autocomplete-suggestion mark_block" data-url="' + item + '" data-header="' + item + '" data-id="' + id + '" data-val = "' + id + '" >' + item.replace(re, "<b>$1</b>") + '</div>';
        },
        onSelect: function(e, term, item){
            $('.mark_block[data-id="'+term+'"]').click();
            $('input[name="input_mark"]').val('');
        }
    });



});

