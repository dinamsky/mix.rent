$( document ).ready(function() {

    var uluru = {lat: $('#map').data('lat')-0, lng: $('#map').data('lng')-0 };
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: uluru
    });
    var marker = new google.maps.Marker({
        position: uluru,
        map: map
    });

});