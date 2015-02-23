(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $deleteUserBtn = $('.delete-user-btn');

        $('#usersTable').DataTable();

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $deleteUserBtn.on('click',function(){

            var id = $(this).data('user-id');

            if(confirm('Are you sure you want to delete user with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/users/' + id,
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