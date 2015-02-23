@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Users</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<a href="/admin/users">
						Users
					</a>
				</li>
				<li><span>Add New User</span></li>
			</ol>
		</div>
	</header>

	{!! Form::model($page,['route' => ['AdminPagesUpdate',$page->id],'method'=>'PUT']) !!}

	<section  class="panel">
		<div class="panel-body">

			<div class="form-group">
				{!! Form::label('title','Title',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::text('title',null,['class'=>'form-control','id'=>'title','required'=>'']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('slug','Slug',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::text('slug',null,['class'=>'form-control','id'=>'slug','required'=>'']) !!}
				</div>
			</div>

			<div class="form-group">
				{!! Form::label('main_content','Main Content',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::textarea('main_content',null,['rows'=>'10','class'=>'form-control','id'=>'description','required'=>'']) !!}
				</div>
			</div>

			@include('partials.forms.seo_details')

			<div class="form-group">
				{!! Form::label('status','Status',['class'=>'col-sm-3 control-label']) !!}
				<div class="col-sm-6">
					{!! Form::select('status',['Inactive'=>'Inactive','Active'=>'Active'],$page->status,['class'=>'form-control']) !!}
				</div>
			</div>

		</div>
	</section>

	{!! Form::submit('Update Page',['class'=>'btn btn-primary']) !!}
	{!! Form::close() !!}
@stop
