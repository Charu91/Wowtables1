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
				{!! Form::label('title','Title',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::text('title',null,['class'=>'form-control','id'=>'title']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('slug','Slug',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::text('slug',null,['class'=>'form-control','id'=>'slug']) !!}
				</div>
			</div>

			<div class="form-group">
				{!! Form::label('main_content','Main Content',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::textarea('main_content',null,['rows'=>'10','class'=>'form-control','id'=>'description']) !!}
				</div>
			</div>

			@include('partials.forms.seo_details')

			<div class="form-group">
				{!! Form::label('status','Status',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::select('status',['Inactive'=>'Inactive','Active'=>'Active'],'Inactive',['class'=>'form-control']) !!}
				</div>
			</div>

		</div>
	</section>


	{!! Form::submit('Add Page',['class'=>'btn btn-primary']) !!}

	{!! Form::close() !!}
@stop