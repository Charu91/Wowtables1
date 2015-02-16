<div class="form-group">
    {!! Form::label('cuisine','Cuisine',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('cuisine',[''=>'','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('collections','Collections',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('collections[]',['0'=>'None','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'','required'=>'']) !!}
    </div>
</div>
