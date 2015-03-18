<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Edit</h2>
    </header>
        {!! Form::model($flag,['route'=>['admin.promotions.flags.update',$flag->id],'method'=>'PUT','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('name','Name',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('name',null,['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('color','Color',['class'=>'col-sm-4 control-label ']) !!}
            <div class="col-sm-8">
                {!! Form::select('color',['Red'=>'Red','Blue'=>'Blue','Green'=>'Green','Yellow'=>'Yellow','Black'=>'Black','White'=>'White'],null,['class'=>'form-control']) !!}
            </div>
        </div>
    </div>
    <footer class="panel-footer">
        {!! Form::submit('Update Flag',['class'=>'btn btn-primary']) !!}
        <a id="cancelFlagEditBtn" class="btn btn-primary">Cancel</a>
    </footer>
    {!! Form::close() !!}
</section>