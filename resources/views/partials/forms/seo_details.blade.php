<div class="form-group">
    {!! Form::label('seo_title','SEO Title',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::text('seo_title',null,['class'=>'form-control','id'=>'description','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('seo_meta_description','SEO Meta Description',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::textarea('seo_meta_description',null,['rows'=>'3','class'=>'form-control','id'=>'description','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('seo_keywords','SEO Keywords',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('seo_keywords',[''=>''],null,['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'']) !!}
    </div>
</div>
