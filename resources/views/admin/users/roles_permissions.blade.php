@extends('templates.admin_layout')

@section('content')

	<header class="page-header">
		<h2>Roles and Permissions</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<a href="/admin/users">
						<i class="fa fa-users"></i>
					</a>
				</li>
				<li><span>Roles and Permissions</span></li>
			</ol>
		</div>
	</header>

	<div class="row">
		<div class="col-sm-6">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title">
						All Roles
					</h2>
				</header>
				<div class="panel-body">
					<table class="table table-striped table-responsive mb-none" id="rolesTable">
						<thead>
						<tr>
							<th>Role Id</th>
							<th>Name</th>
							<th>Actions</th>
						</tr>
						</thead>
						<tbody>
							@foreach($roles as $role)
								<tr>
									<th>{!! $role->id !!}</th>
									<th>{!! $role->name !!}</th>
									<th>
										<a href='javascript:void(0);' title='edit' data-role-id='{!! $role->id !!}'>
											<i class='fa fa-edit'></i>
										</a>
										&nbsp;|&nbsp;
										<a href='javascript:void(0);' title='edit' data-role-id='{!! $role->id !!}'>
											<i class='fa fa-trash-o'></i>
										</a>
									</th>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="col-sm-6">
			<section class="panel panel-featured panel-featured-danger">
				<header class="panel-heading">
					<h2 class="panel-title">
						All Permissions
					</h2>
				</header>
				<div class="panel-body">
					<table class="table table-striped table-responsive mb-none" id="permissionsTable">
						<thead>
						<tr>
							<th>Id</th>
							<th>Action</th>
							<th>Resource</th>
							<th>Belongs To</th>
							<th>Actions</th>
						</tr>
						</thead>
						<tbody>
						@foreach($permissions as $permission)
							<tr>
								<th>{!! $permission->id !!}</th>
								<th>{!! $permission->action !!}</th>
								<th>{!! $permission->resource !!}</th>
								<th>
									@foreach($permission->role as $role)
										{!! $role->name !!} &nbsp;
									@endforeach
								</th>
								<th>
									<a href='javascript:void(0);' title='edit' data-permission-id='{!! $permission->id !!}'>
										<i class='fa fa-edit'></i>
									</a>
									&nbsp;|&nbsp;
									<a href='javascript:void(0);' title='edit' data-permission-id='{!! $permission->id !!}'>
										<i class='fa fa-trash-o'></i>
									</a>
								</th>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>

@stop