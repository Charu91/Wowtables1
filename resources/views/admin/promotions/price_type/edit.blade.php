<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Edit</h2>
    </header>
    {!! Form::model($price_type,['route'=>['admin.promotions.price_type.update',$price_type->id],'method'=>'PUT','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('type_name','Name',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('type_name',null,['class'=>'form-control']) !!}
            </div>
        </div>
    </div>
    <footer class="panel-footer">
        {!! Form::submit('Update Price Type',['class'=>'btn btn-primary']) !!}
        <a id="cancelPriceTypeEditBtn" class="btn btn-primary">Cancel</a>
    </footer>
    {!! Form::close() !!}
</section>