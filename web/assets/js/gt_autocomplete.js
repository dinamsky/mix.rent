$( document ).ready(function() {

    var gt_autoComplete = new autoComplete({
            selector: 'input[name="input_top_gt"]',
            source: function(term, response){
                $.ajax({
                    url: '/ajax/getGtByInput',
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
                return '<div class="autocomplete-suggestion" data-url="' + item + '" data-header="' + item + '" data-id="' + id + '" data-val = "' + item + '" >' + item.replace(re, "<b>$1</b>") + '</div>';
            },
            onSelect: function(e, term, item){
                console.log(e);
                console.log(item);
                console.log(term);

                $('input[name="top_s_gt"]').val(e.target.dataset.id);
                $('input[name="input_top_gt"]').addClass('selected');
            }
        });

});

