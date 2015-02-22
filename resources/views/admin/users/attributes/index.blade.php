@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Users Attributes</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<a href="/admin/dashboard/users">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>User Attributes</span></li>
			</ol>
		</div>
	</header>

	<div class="row">
		<div class="col-sm-8">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title">
						All User Attributes
					</h2>
				</header>
				<div class="panel-body">
					<table class="table table-striped table-responsive mb-none" id="usersTable">
						<thead>
						<tr>
							<th>Attribute Id</th>
							<th>Attribute Name</th>
							<th>Attribute Alias</th>
							<th>Attribute Type</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
						@foreach($attributes as $attribute)
							<tr>
								<th>{!! $attribute->id !!}</th>
								<th>{!! $attribute->name !!}</th>
								<th>{!! $attribute->alias !!}</th>
								<th>{!! $attribute->type !!}</th>
								<th>
									<a data-user-attribute-id="{!! $attribute->id !!}" class="btn btn-xs btn-primary edit-user-attribute">Edit</a>
									&nbsp;|&nbsp;
									<a data-user-attribute-id="{!! $attribute->id !!}" class="btn btn-xs btn-danger delete-user-attribute">Delete</a>
								</th>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="col-sm-4" id="editUserAttributeHolder"></div>
		<div class="col-sm-4">

			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Add New</h2>
				</header>
				<div class="panel-body">
					{!! Form::open(['route'=>'admin.user.attributes.store']) !!}

					@include('admin.users.partials.create_user_attributes')
				</div>
				<footer class="panel-footer">
					{!! Form::submit('Add Attribute',['class'=>'btn btn-primary']) !!}
				</footer>
				{!! Form::close() !!}
			</section>
		</div>
	</div>
@stop