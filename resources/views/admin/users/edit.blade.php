@extends('templates.admin_layout')

@section('content')
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

	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Edit User</h2>
		</header>
		<div class="panel-body">
			{!! Form::model($user, array('route' => array('AdminUserUpdate',$user->id),'method'=>'PUT')) !!}
				@include('admin.users.partials.create_user')
		</div>
		<footer class="panel-footer">
			{!! Form::submit('Update User',['class'=>'btn btn-primary']) !!}
		</footer>
		{!! Form::close() !!}
	</section>
@stop
@stop