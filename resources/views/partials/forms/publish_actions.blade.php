<div class="form-group col-md-4">
    {!! Form::label('publish_date','Date',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('publish_date',null,['class'=>'form-control','data-plugin-datepicker'=>'','required'=>'']) !!}
    </div>
</div>
<div class="form-group col-md-4">
    {!! Form::label('publish_time','Time',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('publish_time','0:00',['class'=>'form-control','data-plugin-timepicker'=>'','required'=>'']) !!}
    </div>
</div>
<div class="col-sm-2">
    {!! Form::submit('Publish',['class'=>'btn btn-block btn-success']) !!}
</div>