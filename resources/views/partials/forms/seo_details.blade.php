<div class="form-group">
    <label class="col-sm-3 control-label" for="attributes[seo_title]">SEO Title <span class="required">*</span></label>
    <div class="col-sm-6">
        {!! Form::text('seo_title',null,['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="attributes[seo_meta_description]">SEO Meta Description <span class="required">*</span></label>
    <div class="col-sm-6">
        {!! Form::textarea('seo_meta_description',null,['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="attributes[seo_meta_keywords]">SEO Keywords <span class="required">*</span></label>
    <div class="col-sm-6">
        {!! Form::select('seo_meta_keywords',[''=>''],null,['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'']) !!}
    </div>
</div>
