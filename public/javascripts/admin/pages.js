(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $deletePageBtn = $('.delete-page-btn');

        $deletePageBtn.on('click',function(){

            var id = $(this).data('page-id');

            if(confirm('Are you sure you want to delete page with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/pages/' + id,
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