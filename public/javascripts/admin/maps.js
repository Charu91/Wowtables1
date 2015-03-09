(function($){

    'use strict';

    $(document).on('ready', function(){

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){

            var $latitude = $('input[name="address[latitude]"]');
            $latitude.focus();

        });

        $('body').delegate('.latLong','focus click keyup',function(){

            var   $latitude = $('input[name="address[latitude]"]').val()
                , $longitude = $('input[name="address[longitude]"]').val();

            if ( $latitude == '' || $longitude == '' )
            {
                $latitude = 19.0772216;
                $longitude = 72.8798309;
            }

            new GMaps({
                div: '#gmap-basic-marker',
                lat: $latitude,
                lng: $longitude
            }).addMarker({
                lat: $latitude,
                lng: $longitude
            });


        });

        $('body').delegate('#location_search','click' ,function(e){

            e.preventDefault();

            var   $latitude = $('input[name="address[latitude]"]')
                , $longitude = $('input[name="address[longitude]"]');

            GMaps.geocode({
                address: $('#location_search_val').val().trim(),
                callback: function(results, status){
                    if(status=='OK'){

                        var latlng = results[0].geometry.location;


                        $latitude.attr('value',latlng.lat()).val(latlng.lat());
                        $longitude.attr('value',latlng.lng()).val(latlng.lng());

                        $latitude.focus();
                    }
                }
            });
        });


    });
})(jQuery);