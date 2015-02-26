<div style="z-index: 999999" class="modal fade" id="addRestaurantAttributeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div style="padding-top:140px;" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add New User Attribute</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        {!! Form::select('type',['boolean'=>'Boolean','datetime'=>'DateTime','float'=>'Float','integer'=>'Integer','multiselect'=>'MultiSelect','singleselect'=>'SingSelect','text'=>'text','varchar'=>'VarChar'],null,['id'=>'restaurantAttributeTypeSelect','placeholder'=>'Select','class'=>'form-control']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        {!! Form::select('restaurant_attributes_list',$restaurant_attributes_list,null,['id'=>'restaurantAttributeSelect','class'=>'form-control', 'data-plugin-selectTwo'=>'']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="addNewRestaurantAttributeSelectBtn" class="btn btn-primary">Confirm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>