(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $editFlagHolder = $('#editFlagHolder')
            , $editFlagBtn = $('.edit-flag-btn')
            , $deleteFlagBtn = $('.delete-flag-btn');

        $editFlagBtn.on('click',function(){

            var id = $(this).data('flag-id');

            $.ajax({
                method: 'GET',
                url: '/admin/promotions/flags/' + id + '/edit'
            }).done(function (editHTML) {
                $editFlagHolder.html(editHTML);
                $editFlagHolder.show();
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $('body').delegate('#cancelFlagEditBtn','click',function(){
            $editFlagHolder.hide();
        });

        $deleteFlagBtn.on('click',function(){

            var id = $(this).data('flag-id');

            if(confirm('Are you sure you want to delete flag with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/promotions/flags/' + id,
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