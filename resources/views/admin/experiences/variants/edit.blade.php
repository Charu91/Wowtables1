<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Edit Variant</h2>
    </header>
    {!! Form::open(['route'=>['admin.experience.variants.update',1],'method'=>'PUT1','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            <label class="col-sm-4 control-label" for="name">Name <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('name',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="slug">Slug <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('slug',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="short_description">Short Description <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::textarea('short_description',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="price_before_tax" class="col-sm-4 control-label">Price Before Tax <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('price_before_tax',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="tax" class="col-sm-4 control-label">Tax <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('tax',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="price_after_tax" class="col-sm-4 control-label">Price After Tax <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('price_after_tax',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <a id="expMenuBtn" data-target="#markdownmodal" data-toggle="modal" class="btn btn-primary">Menu</a>
            </div>
        </div>
        <div id="experienceMenuHolder">
            <div class="form-group">
                <label for="menu" class="col-sm-4 control-label">Menu <span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::textarea('menu',null,['style'=>'overflow:auto;','rows'=>'10','class'=>'form-control','required'=>'','id'=>'expMenu']) !!}
                </div>
            </div>
        </div>
    </div>
    <footer class="panel-footer">
        {!! Form::submit('Update Variant',['class'=>'btn btn-primary']) !!}
    </footer>
    {!! Form::close() !!}
</section>
