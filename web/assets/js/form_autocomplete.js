$( document ).ready(function() {


    new autoComplete({
        selector: 'input[name="user_email"]',
        source: function(term, response){
            $.ajax({
                url: '/ajax/getUser',
                type: 'POST',
                dataType: 'json',
                data: {q: term},
                success: function(json){
                    response(json);
                }
            });
        }
    });

});

