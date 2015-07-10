@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Create New Page</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<a href="/admin/pages">
						Pages
					</a>
				</li>
				<li><span>Add New Page</span></li>
			</ol>
		</div>
	</header>

	{!! Form::open(['route'=>'AdminPagesStore']) !!}

	<section  class="panel">
		<div class="panel-body">

			<div class="form-group">
				{!! Form::label('page_title','Title',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::text('page_title',null,['class'=>'form-control','id'=>'title']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('slug','Slug',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::text('slug',null,['class'=>'form-control','id'=>'slug']) !!}
				</div>
			</div>

			<div class="form-group">
				{!! Form::label('page_contents','Main Content',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::textarea('page_contents',null,['rows'=>'10','class'=>'form-control','id'=>'description']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label" for="attributes[seo_title]">SEO Title <span class="required">*</span></label>
				<div class="col-sm-6">
					{!! Form::text('seo_title',null,['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="attributes[meta_desc]">SEO Meta Description <span class="required">*</span></label>
				<div class="col-sm-6">
					{!! Form::textarea('meta_desc',null,['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="attributes[meta_keywords]">SEO Keywords <span class="required">*</span></label>
				<div class="col-sm-6">
					{{--{!! Form::select('meta_keywords',[''=>''],null,['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'']) !!}--}}
					{!! Form::text('meta_keywords',null,['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'','class'=>'seo_meta_keywords']) !!}
				</div>
			</div>

		</div>
	</section>


	{!! Form::submit('Add Page',['class'=>'btn btn-primary']) !!}
	<a href="/admin/pages">Cancel</a>
	{!! Form::close() !!}
@stop