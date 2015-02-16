<div class="form-group">
    {!! Form::label('title','Title',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::text('title',null,['class'=>'form-control','id'=>'title','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('slug','Slug',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::text('slug',null,['class'=>'form-control','id'=>'slug','required'=>'']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('short_description','Short Description',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::textarea('short_description',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('description','Description',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::textarea('description',null,['rows'=>'10','class'=>'form-control','id'=>'description','required'=>'']) !!}
    </div>
</div>
