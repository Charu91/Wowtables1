(function($){

    'use strict';

    $(document).on('ready', function(){

        var token = $("meta[name='_token']").attr('content');

        $('#usersTable').DataTable({
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

    });
})(jQuery);