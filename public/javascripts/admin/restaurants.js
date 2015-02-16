(function($){

    'use strict';

    $(document).on('ready', function(){

        var token  = $("meta[name='_token']").attr('content');

        $('#restaurantsTable').DataTable({
            lengthChange: false,
            processing: true,
            serverSide: true,
            ajax: '/admin/locations',
            columns: [
                {
                    name: 'location',
                    sortable: true
                },
                {
                    name: 'slug',
                    sortable: false
                },
                {
                    name: 'location_type',
                    sortable: true
                },
                {
                    name: 'parent',
                    sortable: false
                },
                {
                    name: 'actions',
                    sortable: false
                }
            ]
        });

        $('#addNewRestaurantForm').submit(function(e){
            e.preventDefault();
            var input = {};
            $('#addNewRestaurantForm :input').each(function() {
                input[this.name] = $(this).val();
            });

            $.ajax({
                url: '/admin/restaurants',
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

        $('#addNewRestaurantLocationForm').submit(function(e){
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


    });
})(jQuery);