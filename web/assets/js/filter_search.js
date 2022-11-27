$( document ).ready(function() {



    $('#filter_search').on('click', function () {

        document.location.href = getSelectorUrl() + getQueryVars() + '&' + $("#filter_form").serialize();
    });


});
