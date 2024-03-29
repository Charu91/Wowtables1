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

        $('.block-date-picker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1d',
            todayHighlight: true,
            autoclose: true
        });

        $('.block-time-range-date-picker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1d',
            todayHighlight: true,
            autoclose: true
        });

        $('.block-from-time-picker').timepicker({
            showSeconds: true,
            showMeridian: false,
            defaultTime: false
        });

        $('.block-to-time-picker').timepicker({
            showSeconds: true,
            showMeridian: false,
            defaultTime: false
        });

        $('#restaurantPriceIndicator').multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
        });


        $('#restaurantsFlags').multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
        });

        $('#restaurantsGuestCurator').multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
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
        })

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

        $('.select-restaurant,.select-location').on('change',function(){
            var   $selectRestaurant = $('.select-restaurant')
                , $selectLocation = $('.select-location')
                , $generateSlug   = $('.generate-slug');


            var   restaurant = $('.select-restaurant').select2('data').text.toLowerCase().replace(/\s/g, '-')
                , location   = $('.select-location').select2('data').text.toLowerCase().replace(/\s/g, '-');

            var slug = '';
            if(location == "select-location" || restaurant == "select-restaurant"){
                slug = '';
            }
            else{
                slug = restaurant+'-'+location;
            }
            $generateSlug.val(slug);
        });

        $('#menuPicksHolder').hide();

        $('#insertMenuPicks').on('click',function(){
            var menu = $('#menuPicksText').val();
            $('#menuPicksModal').modal('hide');
            $('#menuPicksHolder').show();
            $('#menuPicks').val(menu);
            $('#menuPicksBtn').hide();
        });

        $('#expertTipsHolder').hide();

        $('#insertExpertTips').on('click',function(){
            var menu = $('#expertTipsText').val();
            $('#expertTipsModal').modal('hide');
            $('#expertTipsHolder').show();
            $('#expertTips').val(menu);
            $('#expertTipsBtn').hide();
        });

        $('#termsConditionsHolder').hide();

        $('#insertTermsConditions').on('click',function(){
            var menu = $('#termsConditionsText').val();
            $('#termsConditionsModal').modal('hide');
            $('#termsConditionsHolder').show();
            $('#termsConditions').val(menu);
            $('#termsConditionsBtn').hide();
        });

        $('#addNewBlockDateBtn').on('click',function(){

            var template =  '<div class="col-lg-4 mb-sm block-date-div"><div class="col-lg-10">'+
                '<div class="form-group">'+
                '<label for="block_dates[]" class="col-sm-4 control-label">Dates </label>'+
                '<div class="col-sm-8">'+
                '<input type="text" name="block_dates[]" class="form-control block-date-picker" >'+
                '</div>'+
                '</div>'+
                '</div>'+
                '<div class="col-lg-2">'+
                '<div class="form-group">'+
                '<a class="btn btn-danger delete-block-date-div"><i class="fa fa-times"></i></a>'+
                '</div>'+
                '</div></div>' ;

            $('.block-date-div:last').after(template);

            $('.block-date-picker:last').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '1d',
                todayHighlight: true,
                autoclose: true
            });

        });

        $('body').delegate('.delete-block-date-div','click',function(){

            $(this).closest('.block-date-div').remove();

        });

        var block_times = $('input[name="reset_time_range_limits[]"]').size() + 1;

        $('body').delegate('#addNewBlockTimeRangeBtn','click',function(){

            var template =  '<tr><td><select name="reset_time_range_limits['+block_times+'][limit_by]" class="form-control time-range-limit-by"><option selected="selected" value="Day">Day</option><option value="Date">Date</option></select></td>'+
                '<td><select name="reset_time_range_limits['+block_times+'][day]" class="form-control block-time-range-day-picker"><option value="mon">Monday</option><option value="tue">Tuesday</option><option value="wed">Wednesday</option><option value="thu">Thursday</option><option value="fri">Friday</option><option value="sat">Saturday</option><option value="sun">Sunday</option></select>'+
                '<input type="text" name="reset_time_range_limits['+block_times+'][date]" class="form-control block-time-range-date-picker"></td>'+
                '<td><input type="checkbox" class="form-control full-time-range-picker" name=""></td>'+
                '<td><input size="2" type="text" name="reset_time_range_limits['+block_times+'][from_time]" class="form-control block-from-time-picker"></td>'+
                '<td><input size="2" type="text" name="reset_time_range_limits['+block_times+'][to_time]" class="form-control block-to-time-picker"></td>'+
                '<td><input size="2" type="text" name="reset_time_range_limits['+block_times+'][max_covers_limit]" class="form-control"></td>'+
                '<td><input size="2" type="text" name="reset_time_range_limits['+block_times+'][max_tables_limit]" class="form-control"></td>'+
                '<td><a class="btn btn-danger delete-block-time-range">Remove</a></td></tr>';

            $('#blockTimeRangeTable tr:last').after(template);
            $('.block-time-range-date-picker:last').hide();
            $('.block-from-time-picker:last').timepicker({
                showSeconds: true,
                showMeridian: false,
                defaultTime: false
            });
            $('.block-to-time-picker:last').timepicker({
                showSeconds: true,
                showMeridian: false,
                defaultTime: false
            });

            block_times++;
        });

        $('body').delegate('.delete-block-time-range','click',function(){

            $(this).closest('tr').remove();

            block_times--;
        });


        $('body').delegate('.time-range-limit-by','change',function(){

            var limit_by = $(this).val();
            if(limit_by == 'Date')
            {
                $(this).findNext('.block-time-range-day-picker').prop('disabled', true);
                $(this).findNext('.block-time-range-day-picker').hide();
                $(this).findNext('.block-time-range-date-picker').prop('disabled', false);
                $(this).findNext('.block-time-range-date-picker').show();
                $(this).findNext('.block-time-range-date-picker').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '1d',
                    todayHighlight: true,
                    autoclose: true
                });
            } else {

                $(this).findNext('.block-time-range-day-picker').prop('disabled', false);
                $(this).findNext('.block-time-range-date-picker').prop('disabled', true);
                $(this).findNext('.block-time-range-date-picker').hide();
                $(this).findNext('.block-time-range-day-picker').show();

            }

        });

        $('body').delegate('.full-time-range-picker','change',function(){

            $(this).findNext('.block-from-time-picker').val('');
            $(this).findNext('.block-to-time-picker').val('');
            $(this).findNext('.block-from-time-picker').timepicker({
                showSeconds: true,
                showMeridian: false
            });
            $(this).findNext('.block-to-time-picker').timepicker({
                showSeconds: true,
                showMeridian: false
            });
            $(this).findNext('.block-from-time-picker').prop('readonly', false);
            $(this).findNext('.block-to-time-picker').prop('readonly', false);

            if($(this).is(':checked')) {
                $(this).findNext('.block-from-time-picker').val('00:00:00');
                $(this).findNext('.block-to-time-picker').val('00:00:00');
                $(this).findNext('.block-from-time-picker').timepicker({
                    showSeconds: true,
                    showMeridian: false
                });
                $(this).findNext('.block-to-time-picker').timepicker({
                    showSeconds: true,
                    showMeridian: false
                });
                $(this).findNext('.block-from-time-picker').prop('readonly', true);
                $(this).findNext('.block-to-time-picker').prop('readonly', true);
            }


        });

        var contact_times = $('input[name="contacts[]"]').size() + 1;

        $('body').delegate('#addNewContactsBtn','click',function(){

            var addContactTemplate =  '<tr><td><input type="text" name="contacts['+contact_times+'][name]" class="form-control restaurant-contact-name"></td>'+
                '<td><input type="text" name="contacts['+contact_times+'][designation]" class="form-control restaurant-contact-designation"></td>'+
                '<td><input type="text" name="contacts['+contact_times+'][phone_number]" class="form-control restaurant-contact-phone"></td>'+
                '<td><input type="text" name="contacts['+contact_times+'][email]" class="form-control restaurant-contact-email"></td>'+
                '<td><a class="btn btn-danger delete-restaurant-contact">Remove</a></td></tr>';

            $('#restaurantContactsTable tr:last').after(addContactTemplate);

            contact_times++;
        });

        $('body').delegate('.delete-restaurant-contact','click',function(){

            $(this).closest('tr').remove();

            contact_times--;
        });


    });
})(jQuery);