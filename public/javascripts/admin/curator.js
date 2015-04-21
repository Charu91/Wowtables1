(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $editCuratorHolder = $('#editCuratorHolder')
            , $editCuratorBtn = $('.edit-curator-btn')
            , $deleteCuratorBtn = $('.delete-curator-btn');

        /*$editCuratorBtn.on('click',function(){

            var id = $(this).data('curator-id');

            $.ajax({
                method: 'GET',
                url: '/admin/user/curators/' + id + '/edit'
            }).done(function (editHTML) {
                $editCuratorHolder.html(editHTML);
                $editCuratorHolder.show();
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $('body').delegate('#cancelCuratorEditBtn','click',function(){
            $editCuratorHolder.hide();
        });*/

        $deleteCuratorBtn.on('click',function(){

            var id = $(this).data('curator-id');

            if(confirm('Are you sure you want to delete curator with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/user/curators/' + id,
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