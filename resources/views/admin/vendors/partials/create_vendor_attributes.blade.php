<div class="form-group">
    {!! Form::label('name','Name',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('name',null,['class'=>'form-control','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('alias','Alias',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('alias',null,['class'=>'form-control','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('type','Type',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::select('type',['boolean'=>'Boolean','datetime'=>'DateTime','float'=>'Float','integer'=>'Integer','multiselect'=>'MultiSelect','select_options'=>'SelectOptions','singleselect'=>'SingSlecet','text'=>'text','varchar'=>'VarChar'],null,['placeholder'=>'Select','class'=>'form-control','required'=>'']) !!}
    </div>
</div>