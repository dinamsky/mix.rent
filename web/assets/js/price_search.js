$( document ).ready(function() {



    $('#price_button').on('click', function () {

        // document.location.href = getSelectorUrl() + getQueryVars() + '&'+ $("#price_form").serialize();
        document.location.href = getSelectorUrl() + getQueryVars();
    });


});
