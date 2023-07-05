require([
    'jquery',
    'mage/url'
], function ($, url) {
    'use strict';

    $(document).ready(function () {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

        function successCallback(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            $.ajax({
                url: url.build('/weather/ajax/getlocation'),
                type: 'POST',
                data: {
                    latitude: latitude,
                    longitude: longitude
                },
                success: function(response) {
                    const dataResponse = JSON.parse(JSON.stringify(response));
                    if (dataResponse.status == 'ok') {
                        $("#weather").html("Temperatura: " + dataResponse.temperature + " Humedad: " + dataResponse.humidity);
                    }
                    else{
                        $("#weather").html("No se pudo obtener la información del clima.");
                    }
                },
                error: function() {
                    $("#weather").html("No se pudo obtener la información del clima.");
                }
            });
            
        }

        function errorCallback(error) {
            console.log(error);
        }
    });
});

