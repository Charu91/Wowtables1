<div class="form-group">
    {!! Form::label('restaurant_locations','Locations',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('restaurant_locations[]',['0'=>'None','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'','required'=>'']) !!}
    </div>
</div>
