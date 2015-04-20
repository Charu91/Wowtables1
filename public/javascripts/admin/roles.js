(function($){

    'use strict';

    $(document).on('ready', function(){

        var token = $("meta[name='_token']").attr('content');

        $('#rolesTable').DataTable();

    });
})(jQuery);