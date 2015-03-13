@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Experience Variants</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-dashboard"></i>
					</a>
				</li>
				<li>
					<a href="/admin/experiences">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Experience Variants</span></li>
			</ol>
		</div>
	</header>

	<div class="row">
		<div class="col-sm-6">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title">
						All Variants
					</h2>
				</header>
				<div class="panel-body">
					<table class="table table-striped table-responsive mb-none" id="usersTable">
						<thead>
						<tr>
							<th>Id</th>
							<th>Name</th>
							<th>Slug</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<th>1</th>
								<th>Name</th>
								<th>Slug</th>
								<th>
									<a  class="btn btn-xs btn-primary edit-user-attribute">Edit</a>
									&nbsp;|&nbsp;
									<a  class="btn btn-xs btn-danger delete-user-attribute">Delete</a>
								</th>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="col-sm-6" id=""></div>
		<div class="col-sm-6">

			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Add New Variant</h2>
				</header>
				{!! Form::open(['route'=>'admin.experience.variants.store','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}
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
					{!! Form::submit('Add Variant',['class'=>'btn btn-primary']) !!}
				</footer>
				{!! Form::close() !!}
			</section>
		</div>
	</div>
@stop