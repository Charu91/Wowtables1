(function($){

    'use strict';

    $(document).on('ready', function(){

        var token = $("meta[name='_token']").attr('content');

        $("body").delegate('#schedules_table td','change', function(){
            var schedules = new Array();
            $('input[name="schedules[]"]:checked').each(function() {
                schedules.push(this.value);
            });
        });

        $('body').delegate('.select-all','click', function (event) {
            $(this).parents('tr').find(':checkbox').prop('checked', true);
        });


        $('body').delegate('.select-none','click', function (event) {
            $(this).parents('tr').find(':checkbox').prop('checked', false);
        });



        $("#createSchedule").click( function () {

            var  start_time = $('#start_time').val()
                , end_time = $('#end_time').val();

            $.ajax({
                method: 'GET',
                url: '/admin/restaurants/locations/available_time_slots',
                data : {
                    start_time: start_time,
                    end_time: end_time
                },
                headers: {
                    'X-XSRF-TOKEN': token
                }
            }).done(function (data) {
                $("#schedules_table").html(data)
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

    });
})(jQuery);