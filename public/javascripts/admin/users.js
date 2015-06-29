(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $deleteUserBtn = $('.delete-user-btn')
            , $searchUsers = $('#search_users');

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

        $searchUsers.on('click',function(){
           var users_val = $("#users_search_by").val();

            if(users_val != ""){
                $("#search_loading").css('display','inline');
                $.ajax({
                    method: 'GET',
                    url: '/admin/users/search/' + users_val,
                    headers: {
                        'X-XSRF-TOKEN': token
                    },
                    success:function(response){
                        $("#adminUsersTable tbody").replaceWith(response);
                        $("#custom_pagination").hide();
                        $("#search_loading").css('display','none');
                    }
                }).fail(function (jqXHR) {
                    console.log(jqXHR);
                });
            }
        });


    });
})(jQuery);