            <div class="form-group">
                {!! Form::label('short_description','Short Description',['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('short_description',null,['class'=>'form-control','rows'=>'3']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('description','Description',['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('description',null,['rows'=>'10','class'=>'form-control','id'=>'description']) !!}
                </div>
            </div>
