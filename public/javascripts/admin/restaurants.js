(function($){

    'use strict';

    $(document).on('ready', function(){

        var token  = $("meta[name='_token']").attr('content');

        $('#restaurantsTable').DataTable();

        $('#restaurantDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1d',
            todayHighlight: true,
            autoclose: true
        });

        $('#restaurantTimePicker').timepicker({
            showSeconds: true,
            showMeridian: false
        });

        $('#restaurantLocationDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1d',
            todayHighlight: true,
            autoclose: true
        });

        $('#restaurantLocationTimePicker').timepicker({
            showSeconds: true,
            showMeridian: false
        });


        /*$('#addNewRestaurantLocationForm').submit(function(e){
            e.preventDefault();
            var input = {};
            $('#addNewRestaurantLocationForm :input').each(function() {
                input[this.name] = $(this).val();
            });

            $.ajax({
                url: '/admin/restaurants/locations',
                method: 'Post',
                data: {
                    data: input
                }
            }).done(function(data){
                console.log(data);
            }).fail(function(jqXHR){
                console.log(jqXHR);
            });
        });

        $( "#addNewRestaurantLocationForm" ).validate({
            rules: {
                min_people_per_reservation:{number: true},
                max_reservation_per_day:{number: true},
                max_people_per_reservation:{number: true},
                min_reservation_time_buffer:{number: true},
                max_reservation_per_time_slot:{number: true},
                max_reservation_time_buffer:{number: true},
                commision_per_reservation:{number: true},
                reward_points_per_reservation:{number: true},
                tax:{number: true},
                price_before_tax:{number: true},
                price_after_tax:{number: true},
            },
            ignore: [],
            highlight: function( label ) {
                $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function( label ) {
                $(label).closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function( error, element ) {
                var placement = element.closest('.input-group');
                if (!placement.get(0)) {
                    placement = element;
                }
                if (error.text() !== '') {
                    placement.after(error);
                }
            }
        });

        $( "#addNewRestaurantForm" ).validate({
            ignore: [],
            highlight: function( label ) {
                $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function( label ) {
                $(label).closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function( error, element ) {
                var placement = element.closest('.input-group');
                if (!placement.get(0)) {
                    placement = element;
                }
                if (error.text() !== '') {
                    placement.after(error);
                }
            }
        });*/


        var $deleteRestaurantBtn = $('.delete-restaurant-btn');

        $deleteRestaurantBtn.on('click',function(){

            var id = $(this).data('restaurant-id');

            if(confirm('Are you sure you want to delete restaurant with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/restaurants/' + id,
                    headers: {
                        'X-XSRF-TOKEN': token
                    }
                }).done(function () {
                    location.reload();
                }).fail(function (jqXHR) {
                    console.log(jqXHR);
                });
            }

        });


        var $deleteRestaurantLocationBtn = $('.delete-restaurant-location');

        $deleteRestaurantLocationBtn.on('click',function(){

            var id = $(this).data('restaurant-location-id');

            if(confirm('Are you sure you want to delete restaurant location with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/restaurants/locations/' + id,
                    headers: {
                        'X-XSRF-TOKEN': token
                    }
                }).done(function () {
                    location.reload();
                }).fail(function (jqXHR) {
                    console.log(jqXHR);
                });
            }

        });

    });
})(jQuery);