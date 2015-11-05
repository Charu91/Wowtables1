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
				<li><span>Users</span></li>
			</ol>
		</div>
	</header>

	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title">
				All Users
			</h2>
		</header>

		<div class="panel-body">
			<input type="text" name="search_by" value="" id="users_search_by"/> <a href="javascript:void(0)" class="btn btn-xs btn-primary" id="search_users">Search</a> <span style="display: none;" id="search_loading"><img src="/assets/img/ajax-loader.gif" alt="loading"/></span>
			<table class="table table-striped table-responsive mb-none" id="adminUsersTable">
				<thead>
				<tr>
					<th>User Id</th>
					<th>Full Name</th>
					<th>Email</th>
					<th>Phone</th>
					{{--<th>Status</th>--}}
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							<th>{!! $user->id !!}</th>
							<th>{!! $user->full_name !!}</th>
							<th>{!! $user->email !!}</th>
							<th>{!! $user->phone_number !!}</th>
							{{--<th>{!! $user->status !!}</th>--}}
							<th>
								{!! link_to_route('AdminUserEdit','Edit',$user->id,['target'=>'_blank','class'=>'btn btn-xs btn-primary','data-user-id'=>$user->id]) !!}

								&nbsp;|&nbsp;

								<a data-user-id="{!! $user->id !!}" class="btn btn-xs btn-danger delete-user-btn">Delete</a>
								&nbsp;|&nbsp;
								<a href="/admin/users/{!! $user->id !!}/create_reward" data-user-id="{!! $user->id !!}" class="btn btn-xs btn-primary">Rewards</a>
								&nbsp;|&nbsp;
								<a data-email-id="{!! $user->email !!}" class="btn btn-xs btn-primary" id="ad_forgot_password">Reset Password</a>
								
							</th>
						</tr>
					@endforeach
				</tbody>

			</table>
			<div id="custom_pagination">
				<?php echo $users->render(); ?>
			</div>
		</div>
	</section>

@stop

@section("bottom-script")
	<script>
		$("body").delegate("#ad_forgot_password", "click", function(e) {
			e.preventDefault();
			forgotmail = $(this).attr('data-email-id');
			$.ajax({
				type: "POST",
				url: "/users/forgot_password",
				data: {
					forgotemail: forgotmail
				},
				success: function(e) {
					alert(e);
				}
			});
		});
	</script>
@stop