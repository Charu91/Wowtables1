<div class="mt-lg form-group">
    {!! Form::label('title','Area',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('area',[''=>'','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('title','Location',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('location_id',[''=>'','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>