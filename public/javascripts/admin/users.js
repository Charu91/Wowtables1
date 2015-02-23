(function($){

    'use strict';

    $(document).on('ready', function(){

        var token = $("meta[name='_token']").attr('content');

        $('#usersTable').DataTable();

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

    });
})(jQuery);