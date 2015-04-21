<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Edit Variant</h2>
    </header>
    {!! Form::open(['route'=>['admin.experience.variants.update',1],'method'=>'PUT1','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            <label class="col-sm-4 control-label" for="name">Name <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('name',null,['class'=>'form-control','required'=>'','id'=>'title']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="slug">Slug <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('slug',null,['class'=>'form-control','required'=>'','id'=>'slug']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="attributes[short_description]">Short Description <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::textarea('attributes[short_description]',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="pricing[price]" class="col-sm-4 control-label">Price<span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('pricing[price]',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="pricing[tax]" class="col-sm-4 control-label">Tax <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('pricing[tax]',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="pricing[post_tax_price]" class="col-sm-4 control-label">Post Tax Price <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('pricing[post_tax_price]',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="pricing[commission_per_cover]" class="col-sm-4 control-label">Commissions Per Cover<span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('pricing[commission_per_cover]',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="pricing[commission_on]" class="col-sm-4 control-label">Commission On <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::select('pricing[commission_on]',[''=>'Select Value','Pre-Tax'=>'Pre-Tax','Post-Tax'=>'Post-Tax'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <a id="expMenuBtn" data-target="#markdownmodal" data-toggle="modal" class="btn btn-primary">Menu</a>
            </div>
        </div>
        <div class="form-group">
            <div id="experienceMenuHolder">
                <label for="attributes[menu]" class="col-sm-4 control-label">Menu <span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::textarea('attributes[menu]',null,['style'=>'overflow:auto;','rows'=>'10','class'=>'form-control','required'=>'','id'=>'expMenu']) !!}
                </div>
            </div>
                {!! Form::hidden('attributes[menu_markdown]','',['id'=>'expMarkdownMenu']) !!}
        </div>
        <div class="form-group">
            <label for="mapping[complex_product_id]" class="col-sm-4 control-label">Select Complex Experience <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::select('mapping[complex_product_id]',$complex_experience_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="mapping[variant_option_id]" class="col-sm-4 control-label">Select variant <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::select('mapping[variant_option_id]',$variant_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
            </div>
        </div>
    </div>
    <footer class="panel-footer">
        {!! Form::submit('Update Variant',['class'=>'btn btn-primary']) !!}
    </footer>
    {!! Form::close() !!}
</section>
