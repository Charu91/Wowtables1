(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $editFlagHolder = $('#editFlagHolder')
            , $editFlagBtn = $('.edit-flag-btn')
            , $deleteFlagBtn = $('.delete-flag-btn')
            , $deleteCollectionBtn = $('.delete-collection-btn')
            , $deleteSidebarBtn = $('.delete-sidebar-btn')
            , $deleteEmailFooterPromotionBtn = $('.delete-efp-btn');

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

        $deleteCollectionBtn.on('click',function(){

            var id = $(this).data('collection-id');

            if(confirm('Are you sure you want to delete collection with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/promotions/collections/' + id,
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

        $deleteSidebarBtn.on('click',function(){

            var id = $(this).data('sidebar-id');

            if(confirm('Are you sure you want to delete sidebar with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/promotions/listpage_sidebar/' + id,
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

        $deleteEmailFooterPromotionBtn.on('click',function(){

            var id = $(this).data('efp-id');

            if(confirm('Are you sure you want to delete footer promotion with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/promotions/email_footer_promotions/' + id,
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