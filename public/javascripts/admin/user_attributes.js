(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $editUserAttributeHolder = $('#editUserAttributeHolder')
            , $editUserAttributeBtn = $('.edit-user-attribute')
            , $deleteUserAttributeBtn = $('.delete-user-attribute');

        $editUserAttributeBtn.on('click',function(){

            var id = $(this).data('user-attribute-id');

            $.ajax({
                method: 'GET',
                url: '/admin/user/attributes/' + id + '/edit'
            }).done(function (editHTML) {
                $editUserAttributeHolder.html(editHTML);
                $editUserAttributeHolder.show();
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $('body').delegate('#cancelUserAttributeEditBtn','click',function(){
            $editUserAttributeHolder.hide();
        });

        $deleteUserAttributeBtn.on('click',function(){

            var id = $(this).data('user-attribute-id');

            if(confirm('Are you sure you want to delete attribute with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/user/attributes/' + id,
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