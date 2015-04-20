<div class="form-group">
    {!! Form::label('satus','Status',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('status',[''=>'','active'=>'Active','inactive'=>'Inactive','discontinued'=>'Discontinued'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>
