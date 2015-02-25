(function($){

    'use strict';

    $(document).on('ready', function(){

        var   token = $("meta[name='_token']").attr('content')
            , $editVendorAttributeHolder = $('#editVendorAttributeHolder')
            , $editVendorAttributeBtn = $('.edit-restaurant-attribute')
            , $deleteVendorAttributeBtn = $('.delete-restaurant-attribute')
            , $newVendorAttributeBtn = $('#addNewRestaurantAttributeBtn')
            , $vendorAttributeSelect = $('#restaurantAttributeSelect')
            , $vendorAttributeTypeSelect = $('#restaurantAttributeTypeSelect')
            , $addNewVendorAttributeSelectBtn = $('#addNewRestaurantAttributeSelectBtn');

        $editVendorAttributeBtn.on('click',function(){

            var id = $(this).data('restaurant-attribute-id');

            $.ajax({
                method: 'GET',
                url: '/admin/restaurant/attributes/' + id + '/edit'
            }).done(function (editHTML) {
                $editVendorAttributeHolder.html(editHTML);
                $editVendorAttributeHolder.show();
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $('body').delegate('#cancelVendorAttributeEditBtn','click',function(){
            $editVendorAttributeHolder.hide();
        });

        $('body').delegate('.datepicker','click', function () {

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                zindex: 9999999
            });
        });

        $deleteVendorAttributeBtn.on('click',function(){

            var id = $(this).data('restaurant-attribute-id');

            if(confirm('Are you sure you want to delete attribute with id '+id+'?'))
            {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/restaurant/attributes/' + id,
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

        $newVendorAttributeBtn.on('click',function(){
            $('#addRestaurantAttributeModal').modal('show');
        });

        $addNewVendorAttributeSelectBtn.on('click',function(){

            var   type = $vendorAttributeTypeSelect.val()
                , name = $('#restaurantAttributeSelect option:selected').text()
                , value = $vendorAttributeSelect.val()
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
            $('#addRestaurantAttributesHolder').append('<div class="form-group col-lg-6">'+template+'</div>');
            $('#addRestaurantAttributeModal').modal('hide');

        });

    });
})(jQuery);