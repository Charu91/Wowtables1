(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $editCareerHolder = $('#editCuratorHolder')
            , $editCareerBtn = $('.edit-career-btn')
            , $deleteCareerBtn = $('.delete-career-btn');

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

        $deleteCareerBtn.on('click',function(){

            var id = $(this).data('career-id');

            if(confirm('Are you sure you want to delete career with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/careers/' + id,
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