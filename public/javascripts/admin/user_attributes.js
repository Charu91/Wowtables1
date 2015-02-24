(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $editUserAttributeHolder = $('#editUserAttributeHolder')
            , $editUserAttributeBtn = $('.edit-user-attribute')
            , $deleteUserAttributeBtn = $('.delete-user-attribute')
            , $newUserAttributeBtn = $('#newUserAttributeBtn')
            , $userAttributeSelect = $('#userAttributeSelect')
            , $userAttributeTypeSelect = $('#userAttributeTypeSelect')
            , $addNewUserAttributeSelectBtn = $('#addNewUserAttributeSelectBtn');

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

        $('body').delegate('.datepicker','click', function () {

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                zindex: 9999999
            });
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

        $newUserAttributeBtn.on('click',function(){
            $('#addUserAttributeModal').modal('show');
        });

        $addNewUserAttributeSelectBtn.on('click',function(){

            var   type = $userAttributeTypeSelect.val()
                , name = $('#userAttributeSelect option:selected').text()
                , value = $userAttributeSelect.val()
                , template = '';
            if(type == 'datetime'){
              template = '<label for="attributes['+value+']" class="control-label">'+name+'</label><input name="attributes['+value+']" class="form-control datepicker" type="text" >';
            }
            else if(type == 'boolean'){
                template = '<div style="height: 61px" class="checkbox-custom checkbox-primary"><input class="form-control" type="checkbox" name="attributes['+value+']" type="text"/><label style="padding-top: 25px" for="attributes['+value+']" class="control-label">'+name+'</label></div>';
            }
            else if(type == 'singleselect'){
                template = '<label for="attributes['+value+']" class="control-label">'+name+'</label><input class="form-control" name="attributes['+value+']" type="text">';
            }
            else if(type == 'multiselect'){
                template = '<label for="attributes['+value+']" class="control-label">'+name+'</label><input class="form-control" name="attributes['+value+']" type="text">';
            }
            else if(type == 'text' || type == 'integer' || type == 'float' || type == 'varchar'){
                template = '<label for="attributes['+value+']" class="control-label">'+name+'</label><input class="form-control" name="attributes['+value+']" type="text">';
            }
            $('#addUserAttributeHolder').append('<div class="form-group col-lg-6">'+template+'</div>');
            $('#addUserAttributeModal').modal('hide');

        });

    });
})(jQuery);