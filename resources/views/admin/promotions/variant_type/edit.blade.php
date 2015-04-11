<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Edit</h2>
    </header>
        {!! Form::model($variant_type,['route'=>['admin.promotions.variant_type.update',$variant_type->id],'method'=>'PUT','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('variation_name','Variant Name',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('variation_name',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('variant_alias','Variant Alias',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('variant_alias',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
    </div>
    <footer class="panel-footer">
        {!! Form::submit('Update Variant Type',['class'=>'btn btn-primary']) !!}
        <a id="cancelVariantEditBtn" class="btn btn-primary">Cancel</a>
    </footer>
    {!! Form::close() !!}
</section>